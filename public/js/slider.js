
var swiper = new Swiper('.swiper-container', {
    loop: true,
    speed: 3000,
    initialSlide: 1,
    slidesPerView: 2,
    spaceBetween: 5,
    centeredSlides: true,
    autoplay: {
        delay: 0,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});
