'use strict';

// 変数定義
const eventStart = document.querySelector('.event');
const eventEnd = document.querySelector('.event_end');

// changeイベント
eventStart.addEventListener('change', function () {
    eventEnd.value = eventStart.value;
})