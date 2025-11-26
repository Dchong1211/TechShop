/* FILE: public/assets/js/indexUser.js */

function getScrollAmount(container) {
    if (container.classList.contains('full-width-slider')) {
        return container.clientWidth;
    }
    return 235 * 3; 
}

function scrollSlider(sectionId, direction) {
    const container = document.getElementById(sectionId);
    if (!container) return;

    const scrollAmount = getScrollAmount(container);
    const maxScroll = container.scrollWidth - container.clientWidth;
    
    if (direction === 'left') {
        if (container.scrollLeft <= 10) {
            // Nhảy về cuối tức thì
            container.scrollTo({ left: maxScroll, behavior: 'auto' });
        } else {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    } else {
        if (container.scrollLeft >= maxScroll - 10) {
            // FIX: Nhảy về đầu TỨC THÌ (behavior: 'auto') để không bị cuộn ngược
            container.scrollTo({ left: 0, behavior: 'auto' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
}

function initAutoSlide(id, time = 5000) {
    const container = document.getElementById(id);
    if (!container) return;

    let autoSlideInterval;

    const startSlide = () => {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(() => {
            scrollSlider(id, 'right');
        }, time);
    };

    const stopSlide = () => {
        clearInterval(autoSlideInterval);
    };

    container.addEventListener('mouseenter', stopSlide);
    container.addEventListener('mouseleave', startSlide);
    
    const wrapper = container.closest('.product-slider-wrapper') || container.parentElement;
    if (wrapper) {
        const btns = wrapper.querySelectorAll('.slider-btn');
        btns.forEach(btn => {
            btn.addEventListener('mouseenter', stopSlide);
            btn.addEventListener('mouseleave', startSlide);
            btn.addEventListener('click', () => {
                stopSlide();
                setTimeout(startSlide, 2000); 
            });
        });
    }
    startSlide();
}

document.addEventListener('DOMContentLoaded', function() {
    initAutoSlide('main-banner-slider', 3500);
    initAutoSlide('slider-laptop', 5000);
    initAutoSlide('slider-pc', 6000);
    initAutoSlide('slider-gear', 5500);
});