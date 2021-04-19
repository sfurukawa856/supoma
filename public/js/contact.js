'use strict';
import { disabled } from "./disabled.js";

// 変数定義
const contact = document.querySelector('#contact');
const btn_submit = document.querySelector('#btn_submit');

// 読み込み時コメントボタン無効
window.addEventListener('load', disabled(btn_submit, true));

// コメント入力時コメントボタン有効
contact.addEventListener('keyup', function () {
    if (contact.value.length > 0) {
        disabled(btn_submit, false);
    } else {
        disabled(btn_submit, true);
    }
});