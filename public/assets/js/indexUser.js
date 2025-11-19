/* --- public/assets/js/indexUser.js --- */

// Hàm cuộn slider (Chạy được cho cả nút bấm và tự động)
function scrollSlider(sectionId, direction) {
    const container = document.getElementById(sectionId);
    if (!container) return;

    const scrollAmount = 235; // Chiều rộng thẻ (220px) + khoảng cách (15px)
    const maxScroll = container.scrollWidth - container.clientWidth;
    
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount * 3, behavior: 'smooth' });
    } else {
        // Logic vòng lặp: Nếu cuộn gần hết (sai số 10px) thì quay về đầu
        if (container.scrollLeft >= maxScroll - 10) {
            container.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount * 3, behavior: 'smooth' });
        }
    }
}

// Hàm cài đặt tự động chạy cho từng slider
function initAutoSlide(id) {
    const container = document.getElementById(id);
    if (!container) return;

    let autoSlideInterval;

    // Hàm bắt đầu chạy
    const startSlide = () => {
        autoSlideInterval = setInterval(() => {
            scrollSlider(id, 'right');
        }, 4000); // 4 giây chạy 1 lần
    };

    // Hàm dừng chạy
    const stopSlide = () => {
        clearInterval(autoSlideInterval);
    };

    // Di chuột vào thì dừng, bỏ ra thì chạy tiếp
    container.addEventListener('mouseenter', stopSlide);
    container.addEventListener('mouseleave', startSlide);
    
    // Áp dụng cho cả nút bấm xung quanh (để khi bấm nút không bị xung đột)
    const wrapper = container.closest('.product-slider-wrapper');
    if (wrapper) {
        const btns = wrapper.querySelectorAll('.slider-btn');
        btns.forEach(btn => {
            btn.addEventListener('mouseenter', stopSlide);
            btn.addEventListener('mouseleave', startSlide);
        });
    }

    // Bắt đầu chạy ngay
    startSlide();
}

// Kích hoạt khi web tải xong
document.addEventListener('DOMContentLoaded', function() {
    initAutoSlide('slider-laptop');
    initAutoSlide('slider-pc');
    initAutoSlide('slider-gear');
});

// Hàm tính toán khoảng cách cuộn (Thông minh hơn)
function getScrollAmount(container) {
    // Nếu là Banner lớn (Full width) -> Cuộn đúng bằng chiều rộng banner
    if (container.classList.contains('full-width-slider')) {
        return container.clientWidth;
    }
    // Nếu là danh sách sản phẩm -> Cuộn 3-4 sản phẩm một lúc
    return 235 * 3; 
}

// Hàm cuộn slider
function scrollSlider(sectionId, direction) {
    const container = document.getElementById(sectionId);
    if (!container) return;

    const scrollAmount = getScrollAmount(container);
    const maxScroll = container.scrollWidth - container.clientWidth;
    
    if (direction === 'left') {
        // Nếu đang ở đầu mà bấm lùi -> Nhảy xuống cuối (Loop)
        if (container.scrollLeft <= 10) {
            container.scrollTo({ left: maxScroll, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    } else {
        // Nếu đang ở cuối mà bấm tới -> Quay về đầu (Loop)
        if (container.scrollLeft >= maxScroll - 10) {
            container.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
}

// Hàm cài đặt tự động chạy
function initAutoSlide(id, time = 4000) {
    const container = document.getElementById(id);
    if (!container) return;

    let autoSlideInterval;

    const startSlide = () => {
        autoSlideInterval = setInterval(() => {
            scrollSlider(id, 'right');
        }, time);
    };

    const stopSlide = () => {
        clearInterval(autoSlideInterval);
    };

    // Di chuột vào thì dừng, bỏ ra thì chạy tiếp
    container.addEventListener('mouseenter', stopSlide);
    container.addEventListener('mouseleave', startSlide);
    
    // Áp dụng cho nút bấm xung quanh
    const wrapper = container.closest('.product-slider-wrapper') || container.parentElement;
    if (wrapper) {
        const btns = wrapper.querySelectorAll('.slider-btn');
        btns.forEach(btn => {
            btn.addEventListener('mouseenter', stopSlide);
            btn.addEventListener('mouseleave', startSlide);
        });
    }

    startSlide();
}

// Kích hoạt tất cả Slider khi web tải xong
document.addEventListener('DOMContentLoaded', function() {
    // Slider Banner Chính (Chạy nhanh hơn chút: 3.5s)
    initAutoSlide('main-banner-slider', 3500);
    
    // Slider Sản phẩm (4s)
    initAutoSlide('slider-laptop', 4000);
    initAutoSlide('slider-pc', 4500); // Lệch thời gian chút cho tự nhiên
    initAutoSlide('slider-gear', 5000);
});