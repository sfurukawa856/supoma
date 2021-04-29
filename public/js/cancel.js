'use strict';

const cancelCheck = document.querySelector('.cancel_checkbox');
const cancelBtn = document.querySelector('input[type="submit"]');

cancelCheck.addEventListener('change', function () {
    cancelBtn.classList.toggle('disabled_submit');
})