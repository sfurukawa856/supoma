
var swiper = new Swiper('.swiper-container', {
    loop: true,
    speed: 3000,
    autoplay: {
        delay: 1000,
        disableOnInteraction: false,
    },
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    coverflowEffect: {
        rotate: 50,
        stretch: 10,
        depth: 100,
        modifier: 1,
        slideShadows: true,
    },
});
