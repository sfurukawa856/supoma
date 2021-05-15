'use strict';
import { jsonData } from "./call_json.js";
import { now } from "./end_apply.js";
// 変数定義
const btn = document.querySelector('#readmore');
const table = document.querySelector('.table');
let counter = 0;
const jsonUrl = "http://localhost/GroupWork/20210329_spoma-main/public/json/data.json";
// jsonファイルの読み込み
btn.addEventListener('click', function () {
    counter++;
    jsonData(jsonUrl).then(function (value2) {
        for (let j = 0; j <= 2; j++) {
            let counter2 = counter * 3 + j;
            const el = value2[counter2];
            if (el) {
                // クラス名table-itemのdivを作りtableの中に挿入
                const div_tableItem = document.createElement('div');
                div_tableItem.classList.add('table-item');
                if (el["end_time"] < now) {
                    div_tableItem.classList.add('past');
                } else if (el["end_time"] === now) {
                    div_tableItem.classList.add('future');
                } else if (el["end_time"] >= now) {
                    div_tableItem.classList.add('future');
                }
                table.appendChild(div_tableItem);

                // formタグを作りtable-itemの中に挿入
                const form = document.createElement('form');
                form.name = 'form' + counter2;
                form.action = '../../Individual/personal/';
                form.method = 'post';
                div_tableItem.appendChild(form);

                // input type="hidden"を三つ作りformの中に挿入
                const hidden1 = document.createElement('input');
                hidden1.type = 'hidden';
                hidden1.name = 'user_id';
                hidden1.value = el["userpost_id"];
                form.appendChild(hidden1);

                const hidden2 = document.createElement('input');
                hidden2.type = 'hidden';
                hidden2.name = 'insert_date';
                hidden2.value = el["insert_date"];
                form.appendChild(hidden2);

                // aタグを作りformの中に挿入
                const link = document.createElement('a');
                link.href = "javascript:form" + counter2 + ".submit()";
                form.appendChild(link);

                // クラス名image-containerのdivを作りformの中に挿入
                const div_imageContainer = document.createElement('div');
                div_imageContainer.classList.add('image-container');
                link.appendChild(div_imageContainer);

                // imgタグを作りimage-containerの中に挿入
                const img = document.createElement('img');
                img.src = el["file_path"];
                div_imageContainer.appendChild(img);

                // クラス名table-item-textのdivを作りformの中に挿入
                const div_tableItemText = document.createElement('div');
                div_tableItemText.classList.add('table-item-text');
                link.appendChild(div_tableItemText);

                // クラス名table-item-text-leftのdivを作りtable-item-textの中に挿入
                const div_tableItemTextLeft = document.createElement('div');
                div_tableItemTextLeft.classList.add('table-item-text-left');
                div_tableItemText.appendChild(div_tableItemTextLeft);

                // h3を作りtable-item-textの中に挿入
                const h3_title = document.createElement('h3');
                const title_el = el["title"];
                h3_title.textContent = title_el;
                div_tableItemText.appendChild(h3_title);

                // クラス名categoryのpタグを作りtable-item-text-leftの中に挿入
                const p_category = document.createElement('p');
                p_category.classList.add('category');
                const category_el = el["category"];
                p_category.textContent = category_el;
                div_tableItemTextLeft.appendChild(p_category);

                // 実施日用のpタグを作りtable-item-text-leftの中に挿入
                const p_eventDate = document.createElement('p');
                const eventDate_el = el["eventDate"].substr(5, 11);
                p_eventDate.textContent = "開催日：" + eventDate_el;
                div_tableItemTextLeft.appendChild(p_eventDate);

                // メッセージ用のpタグを作りformの中に挿入
                const p_message = document.createElement('p');
                p_message.classList.add('resultMessage');
                if (el["message"].length >= 120) {
                    var message_el = el["message"].substr(0, 119) + "…";
                } else {
                    var message_el = el["message"];
                }
                p_message.insertAdjacentHTML('beforeend', message_el);
                link.appendChild(p_message);
            } else {
                btn.remove();
            }
        }
    })
})

// もっと見るボタンの有効無効
window.addEventListener('load', function () {
    btn.disabled = true;
    btn.style.color = "#B0B0B0";

    const itemCount = document.querySelectorAll('.table-item');
    jsonData(jsonUrl).then(function (value) {
        if (value.length - itemCount.length > 0) {
            btn.disabled = false;
            btn.style.color = "#000";
        }
    });
})

