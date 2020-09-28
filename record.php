<?php
session_start();
require 'pdo_connect.php';
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    #URLがhttpsじゃなかったらリダイレクトする｡
    
    $members = $dbh->prepare('SELECT * FROM hoot_user WHERE id=?');
    $members->execute(array(
        $_SESSION['id']
    ));
    $member = $members->fetch();

    if (!empty($_POST)) {
        if (empty($error)) {
            $hashtag_id = "";
            for ($i = 0; $i < 30; $i++) {
                $hashtag_id .= mt_rand(0, 9);
            }

            $hashtag = $dbh->prepare('INSERT INTO hoot_hashtag (id, category) VALUES(?,?)');
            $hashtag->execute(array(
                $hashtag_id,
                $_POST['category'],
            ));

            $sound = $dbh->prepare('UPDATE hoot_sound SET hoot_hashtag_id = ? WHERE user_id=? AND hoot_hashtag_id is null;');

            $sound->execute(array(
                $hashtag_id,
                $_SESSION['id'],
            ));

            header('Location: index.php');
            exit();
        }
    }else {
        $error['empty'] = 'empty';
    }
}else {
    header('Location: signin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Record</title>
    <link rel="stylesheet" href="./css/reboot.min.css"/>
    <link rel="stylesheet" href="./css/record.css"/>
</head>
<body>
<div class="global-container">
    <header class="header">
        <img src="./icon/hoot_logo.svg" alt="hoot img" class="header__logo"/>
        <!-- <img src="./icon/search.png" alt="search img" class="header__search"> -->
        <!-- <a href="signin.html" class="header__signout">ログアウト</a> -->
    </header>
    <div class="user-icon">
        <img src="./icon/bubble_hoot.svg" alt="bubble image" class="bubble">
        <img src="./icon/<?php echo $member['picture']?>_sitting.svg" alt="owl image" class="sitting-owl">
    </div>
    <div class="main-container">
        <div class="record">
            <div class="record__btn">
                <button class="record__start" onclick="startRecording(this);">録音</button>
                <button class="record__stop" onclick="stopRecording(this);" disabled>停止</button>
            </div>
            <ul id="recordingslist"></ul>
        </div>
        <?php if (empty($_GET)):?>
            <form action="" class="form" method="post">
                <select name="category" class="form__info" required>
                    <option value="myself" selected>独り言</option>
                    <option value="consult">相談</option>
                    <option value="song">song</option>
                    <option value="sos">SOS</option>
                    <option value="others">その他</option>
                </select><br>
                <div class="form__btn-wrapper">
                    <button class="form__btn" type="submit">飛ばす</button>
                </div>
                <button class="back-btn" onclick="location.href='index.php'" type="submit">
                    <img src="./icon/arrow.png" alt="arrow image">
                </button>
            </form>
        <?php else:?>
            <form action="response.php" class="form" method="post">
                <div class="form__btn-wrapper">
                    <input type="hidden" value="reply" name="category">
                    <input type="hidden" value="<?php print($_GET['res_id']);?>" name="res_id">
                    <input type="hidden" value="1" name="res">
                    <button class="form__btn" type="submit">飛ばす</button>
                </div>
            </form>
        <?php endif;?>
        <button class="back-btn" onclick="location.href='index.php'">
            <img src="./icon/arrow.png" alt="arrow image">
        </button>
    </div>
    <div style="color:rgb(247,247,247);">
        <pre id="log"></pre>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
    function __log(e, data) {
        log.innerHTML += "\n" + e + " " + (data || '');
    }

    var audio_context;
    var recorder;

    function startUserMedia(stream) {
        var input = audio_context.createMediaStreamSource(stream);
        audio_context.resume();
        __log('Media stream created.');

        // Uncomment if you want the audio to feedback directly
        //input.connect(audio_context.destination);
        //__log('Input connected to audio context destination.');

        recorder = new Recorder(input);
        __log('Recorder initialised.');
    }

    function startRecording(button) {
        recorder && recorder.record();
        button.disabled = true;
        button.nextElementSibling.disabled = false;
        __log('Recording..');
    }

    function stopRecording(button) {
        recorder && recorder.stop();
        button.disabled = true;
        button.previousElementSibling.disabled = false;
        __log('Stopped recording.');

        // create WAV download link using audio data blob
        createDownloadLink();

        recorder.clear();
    }


    function createDownloadLink() {
        recorder && recorder.exportWAV(function (blob) {
            var url = URL.createObjectURL(blob);
            var li = document.createElement('li');
            var au = document.createElement('audio');
            var hf = document.createElement('a');

            au.controls = true;
            au.src = url;
            hf.href = url;
            hf.download = new Date().toISOString() + '.wav';
            hf.innerHTML = hf.download;
            li.appendChild(au);
            li.appendChild(hf);
            recordingslist.appendChild(li);

            var random = Math.round(Math.random() * 1000000);
            var data = new FormData();
            data.append('fname', random)
            data.append('sound', blob, 'j.wav');

            $.ajax({
                url: "./recup/upload.php",
                type: 'POST',
                data: data,
                contentType: false,
                processData: false
            }).done(function (data) {
                console.log(data);
            });
        });
    };


    window.onload = function init() {
        try {
            // webkit shim
            window.AudioContext = window.AudioContext || window.webkitAudioContext;
            if (navigator.mediaDevices === undefined) {
                navigator.mediaDevices = {};
            }
            if (navigator.mediaDevices.getUserMedia === undefined) {
                navigator.mediaDevices.getUserMedia = function (constraints) {
                    // First get ahold of the legacy getUserMedia, if present
                    let getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

                    // Some browsers just don't implement it - return a rejected promise with an error
                    // to keep a consistent interface
                    if (!getUserMedia) {
                        return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
                    }

                    // Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
                    return new Promise(function (resolve, reject) {
                        getUserMedia.call(navigator, constraints, resolve, reject);
                    });
                }
            }
            window.URL = window.URL || window.webkitURL;

            audio_context = new AudioContext;
            __log('Audio context set up.');
            __log('navigator.mediaDevices ' + (navigator.mediaDevices.length != 0 ? 'available.' : 'not present!'));
        } catch (e) {
            alert('No web audio support in this browser!');
        }

        navigator.mediaDevices.getUserMedia({audio: true})
            .then(function (stream) {
                startUserMedia(stream);
            })
            .catch(function (e) {
                __log('No live audio input: ' + e);
            });
    };
</script>

<script src="./recup/js/recorder.js"></script>
</body>
</html>
