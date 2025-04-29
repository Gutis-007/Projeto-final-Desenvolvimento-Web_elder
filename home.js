debugger
        let variable = 1;
        let anotherVariable = 2;
        let soma = variable + anotherVariable;
        document.addEventListener("DOMContentLoaded", function () {
            new Swiper('.swiper', {
                slidesPerView: 3,
                spaceBetween: 20,
                direction: 'horizontal',
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                scrollbar: {
                    el: '.swiper-scrollbar',
                },
            });
        });