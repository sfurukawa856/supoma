'use strict';

const today = new Date();
const year = today.getFullYear();
let month = today.getMonth() + 1;
let date = today.getDate();
const now = year + '-' + String(month).padStart(2, 0) + '-' + String(date).padStart(2, 0);

window.addEventListener('load', end(now));

function end(date) {
    let table_items = document.querySelectorAll('.table-item');
    for (let i = 0; i < table_items.length; i++) {
        let end_time = table_items[i].lastElementChild.end_time.value;
        if (date > end_time) {
            table_items[i].classList.add('past');
        } else if (date === end_time) {
            table_items[i].classList.add('future');
        } else if (date <= end_time) {
            table_items[i].classList.add('future');
        }
    }
}

export { now };