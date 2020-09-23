<?php
session_start();
require 'pdo_connect.php';
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
    if (!empty($_POST)) {
        if (empty($error)) {
            $hashtag_id = "";
            for ($i = 0; $i < 30; $i++) {
                $hashtag_id .= mt_rand(0, 9);
            }

            $hashtag = $dbh->prepare('INSERT INTO hoot_hashtag (id, generation, gender, freeword) VALUES(?,?,?,?)');
            $hashtag->execute(array(
                $hashtag_id,
                $_POST['generation'],
                $_POST['gender'],
                $_POST['freeword'],
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
    </header>
    <div class="main-container">
        <h2 class="page-title">つぶやき</h2>
        <div class="record">
            <div class="record__btn">
                <button class="record__start" onclick="startRecording(this);">録音</button>
                <button class="record__stop" onclick="stopRecording(this);" disabled>停止</button>
            </div>
        </div>
        <ul id="recordingslist" style="list-style-type: none; margin: 0 auto;"></ul>
        <h2 class="page-title">ハッシュタグ</h2>
        <form action="record.php" method="post">
            <label for="generation" class="form__title">年代</label>
            <select name="generation" class="form__info" required>
                <option value=""></option>
                <option value="10">10代</option>
                <option value="20">20代</option>
                <option value="30">30代</option>
                <option value="40">40代</option>
                <option value="50">50代</option>
                <option value="60">60代</option>
                <option value="70">70代</option>
                <option value="80">80代</option>
                <option value="90">90代</option>
            </select><br>
            <label for="gender" class="form__title">性別</label>
            <select name="gender" class="form__info" required>
                <option value=""></option>
                <option value="boy">男性</option>
                <option value="girl">女性</option>
                <option value="others">その他</option>
                <option value="secret">無回答</option>
            </select><br>
            <label for="freeword" class="form__title">フリーワード</label>
            <textarea name="freeword" class="form__info" placeholder="#嬉しい" required></textarea>

            <?php if ($error['empty'] === 'empty') : ?>
                <p>録音停止後､すべての空欄を埋めてください｡</p>
            <?php endif; ?>

            <div class="form__btn-wrapper">
                <button class="form__btn" type="submit">公開</button>
            </div>
        </form>
    </div>
    <button class="back-btn" onclick="history.back()">
        <img src="./icon/arrow.png" alt="arrow image">
    </button>
</div>
<h2>Log</h2>
<pre id="log"></pre>
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
