document.addEventListener("DOMContentLoaded", function () {

    const slides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    const indicators = document.querySelectorAll('.carousel-indicator');

    let index = 0;
    const total = slides.length;

    if (total === 0) return;

    function showSlide(i) {

        if (i >= total) i = 0;
        if (i < 0) i = total - 1;

        slides.forEach((slide, n) => {
            slide.style.display = (n === i) ? "block" : "none";
        });

        indicators.forEach((ind, n) => {
            ind.classList.toggle("active", n === i);
        });

        index = i;
    }

    function nextSlide() {
        showSlide(index + 1);
    }

    function prevSlide() {
        showSlide(index - 1);
    }

    let interval = setInterval(nextSlide, 5000);

    function resetInterval() {
        clearInterval(interval);
        interval = setInterval(nextSlide, 5000);
    }

    nextBtn?.addEventListener("click", () => {
        nextSlide();
        resetInterval();
    });

    prevBtn?.addEventListener("click", () => {
        prevSlide();
        resetInterval();
    });

    indicators.forEach((ind, i) => {
        ind.addEventListener("click", () => {
            showSlide(i);
            resetInterval();
        });
    });

    showSlide(0);
});