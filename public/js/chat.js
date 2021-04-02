'use strict';
import { disabled } from "./disabled.js";
import { jsonData } from "./call_json.js";
// 変数定義
const message = document.querySelector('#message');
const submitBtn = document.querySelector('#submit');

// 読み込み時コメントボタン無効
window.addEventListener('load', disabled(submitBtn, true));

// コメント入力時コメントボタン有効
message.addEventListener('keyup', function () {
    if (message.value.length > 0) {
        disabled(submitBtn, false);
    } else {
        disabled(submitBtn, true);
    }
});

// 非同期通信
document.addEventListener('DOMContentLoaded', function () {
    submitBtn.addEventListener('click', function () {
        const formDatas = document.querySelector('.form');
        const postDatas = new FormData(formDatas);

        const request = new XMLHttpRequest();
        request.open('POST', './action/xhr.php', true);
        request.send(postDatas);

        // 通信状態ごとに関数呼び出し
        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    const jsonUrl = "http://localhost/GroupWork/20210329_spoma-main/public/js/chatData.json";
                    jsonData(jsonUrl).then(function (value) {
                        console.log(value['chat_message'].split('\n'));
                        console.log(value['chat_message'].split('\n').join('<br>'));
                        // let newText = textArray.join('<br>');
                        // ログインユーザーとコメントユーザーのIDチェック
                        if (value['commentUserID'] !== value['postUserID']) {
                            var talkItem = document.querySelector('.talk-items-user');
                            var talkItems = document.querySelectorAll('.talk-items-user');
                            var talkItemNone = document.querySelector('.talk-items-user.none');
                        } else {
                            var talkItem = document.querySelector('.talk-items-my');
                            var talkItems = document.querySelectorAll('.talk-items-my');
                            var talkItemNone = document.querySelector('.talk-items-my.none');
                        }

                        if (talkItems.length === 1) {
                            const clone_talkItem = talkItem.cloneNode(true);
                            talkItem.after(clone_talkItem);
                            talkItem.classList.remove('none');

                            // アイコン表示
                            const img_wrap = talkItem.querySelector('.img-wrap');
                            const img = document.createElement('img');
                            img.src = value['commentUserFilepath'].substr(3);
                            img_wrap.appendChild(img);
                            // ニックネーム表示
                            const nickname = talkItem.querySelector('.nickname');
                            nickname.textContent = value['commentUserNickname'];
                            // メッセージ表示
                            const talk = talkItem.querySelector('.talk');
                            // talk.textContent = value['chat_message'].split('\n').join('<br>');
                            talk.insertAdjacentHTML('beforeend', value['chat_message'].split('\n').join('<br>'));
                        } else {
                            const clone_talkItemNone = talkItemNone.cloneNode(true);
                            talkItemNone.after(clone_talkItemNone);
                            talkItemNone.classList.remove('none');

                            // console.log(talkItems);
                            // const talkItemCounter = talkItems.length;
                            const talkItemCounter = talkItems[talkItems.length - 1];

                            // アイコン表示
                            const img_wrap = talkItemCounter.querySelector('.img-wrap');
                            const img = document.createElement('img');
                            img.src = value['commentUserFilepath'].substr(3);
                            img_wrap.appendChild(img);
                            // ニックネーム表示
                            const nickname = talkItemCounter.querySelector('.nickname');
                            nickname.textContent = value['commentUserNickname'];
                            // メッセージ表示
                            const talk = talkItemCounter.querySelector('.talk');
                            // talk.textContent = value['chat_message'].split('\n').join('<br>');
                            talk.insertAdjacentHTML('beforeend', value['chat_message'].split('\n').join('<br>'));

                            console.log('hoge');
                            // console.log(talkItemUserNone);
                        }
                    })
                    // const jsonData = request.responseText;
                    document.querySelector(".form-btn").insertAdjacentHTML('beforeend', request.responseText);
                } else {
                    alert('サーバーエラーが発生しました。');
                    location.href = '../../Individual/personal.php';
                }
            } else {
                console.log('通信中…');
            }
        };

        if (message.value.length > 0) {
            message.value = "";
            disabled(submitBtn, true);
        }

        // function EncodeHTMLForm(data) {
        //     var params = [];

        //     for (var name in data) {
        //         var value = data[name];
        //         var param = encodeURIComponent(name) + '=' + encodeURIComponent(value);

        //         params.push(param);
        //     }

        //     console.log(params);

        //     return params.join('&').replace(/%20/g, '+');
        // }
    })
})