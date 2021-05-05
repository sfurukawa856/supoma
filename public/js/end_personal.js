'use strict';
import { now } from "./end_apply.js";
import { disabled } from "./disabled.js";

// 変数定義
const end_time = document.querySelector('.end_time');
const mainBtn = document.querySelector('.main-btn');
const member = document.querySelector('.member');
const end_member = document.querySelector('.member-end');
const textarea = document.querySelector('textarea');

window.addEventListener('load', function () {
    // 募集期間チェック
    if (now > end_time.value) {  //過去の投稿ならば
        if (mainBtn !== null) {
            disabled(mainBtn, true);
            mainBtn.style.pointerEvents = "none";
        }
        member.innerHTML = "<p class='member-end'>募集期間終了。</p>";
        textarea.style.pointerEvents = "none";
        textarea.placeholder = "コメントできません"
    } else if (now === end_time.value) {  //現在の投稿ならば
        disabled(mainBtn, false);
    } else if (now <= end_time.value) {  //現在以降の投稿ならば
        disabled(mainBtn, false);
    }

    // 人数チェック
    if (end_member !== null) {
        disabled(mainBtn, true);
        mainBtn.style.pointerEvents = "none";
    }
})