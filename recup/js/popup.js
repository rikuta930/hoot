const reportBtn = document.querySelector('.js-report-btn');
const globalContainer = document.querySelector('.global-container');
const closeBtn = document.querySelector('.js-close-btn');

reportBtn.addEventListener('click', function() {
  globalContainer.classList.add('open-popup');
});

closeBtn.addEventListener('click', function() {
  globalContainer.classList.remove('open-popup');
});