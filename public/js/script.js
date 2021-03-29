
let $headerInfos = document.querySelectorAll('.headerInfo');
// console.log($headerInfos);

let $headerMypage = document.querySelector('.header-mypage');


let $bgTransparent = document.querySelector('.bg-transparent');
// console.log($bgTransparent);


$headerInfos.forEach(function (item, index) {
    item.addEventListener('click', () => {
        console.log("res");
        $headerMypage.classList.toggle('display');
        $bgTransparent.classList.toggle('display');
    })
})


$bgTransparent.addEventListener('click', () => {
    $headerMypage.classList.toggle('display');
    $bgTransparent.classList.toggle('display');
})