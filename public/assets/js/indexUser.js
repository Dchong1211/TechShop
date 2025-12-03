/* FILE: public/assets/js/indexUser.js
 * MỤC TIÊU:
 * - Slider hero + slider sản phẩm dùng chung 1 engine
 * - Tự tính scrollAmount dựa trên width container & card
 * - Hạn chế layout thrashing (đo 1 lần, cache, update khi resize)
 * - Giữ nguyên API: scrollSlider(sectionId, direction)
 */

(function () {
    // Registry lưu thông tin mỗi slider theo id
    const sliderRegistry = {};
    const autoSlideTimers = {};
    let resizeTimer = null;

    // List id slider đang dùng ở index (nếu sau này thêm, chỉ cần bổ sung)
    const SLIDER_IDS = [
        'main-banner-slider',
        'slider-laptop',
        'slider-pc',
        'slider-monitor',
        'slider-components',
        'slider-gear',
        'slider-accessories'
    ];

    function isHeroSlider(container) {
        return container.classList.contains('full-width-slider');
    }

    /**
     * Đo kích thước 1 slider:
     * - Với hero: scrollAmount = full width
     * - Với product: auto tính theo card width * số card / view
     */
    function measureSlider(container) {
        if (!container || !container.id) return;

        const id = container.id;
        const rect = container.getBoundingClientRect();
        if (!rect.width) return;

        const hero = isHeroSlider(container);
        let scrollAmount;

        if (hero) {
            scrollAmount = rect.width;
        } else {
            // Tìm 1 item để đo: ưu tiên .product-card
            const item =
                container.querySelector('.product-card') ||
                container.querySelector('.banner-item') ||
                container.firstElementChild;

            let itemWidth = item ? item.getBoundingClientRect().width : rect.width;

            if (!itemWidth || itemWidth <= 0) {
                itemWidth = rect.width; // fallback
            }

            // Số item hiển thị trên 1 view (ước lượng)
            let perView = Math.floor(rect.width / itemWidth);
            if (!perView || perView < 1) perView = 1;
            if (perView > 5) perView = 5; // không cần quá nhiều

            scrollAmount = itemWidth * perView;
        }

        sliderRegistry[id] = {
            scrollAmount,
        };
    }

    function measureAllSliders() {
        SLIDER_IDS.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                measureSlider(el);
            }
        });
    }

    // Debounce resize để không đo liên tục
    function onResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            measureAllSliders();
        }, 150);
    }

    /**
     * Cuộn slider theo hướng, giữ API cũ
     * - Hero: loop vòng (đầu <-> cuối)
     * - Product: cuộn mượt, không loop nhảy thô
     */
    window.scrollSlider = function scrollSlider(sectionId, direction) {
        const container = document.getElementById(sectionId);
        if (!container) return;

        if (!sliderRegistry[sectionId]) {
            measureSlider(container);
        }
        const { scrollAmount } = sliderRegistry[sectionId] || {};
        if (!scrollAmount || scrollAmount <= 0) return;

        const maxScroll = container.scrollWidth - container.clientWidth;
        const current = container.scrollLeft;
        const hero = isHeroSlider(container);

        if (hero) {
            // HERO: cho loop mềm (nhảy tức thì không animate, nhưng chỉ 1 frame)
            if (direction === 'left') {
                if (current <= 5) {
                    container.scrollTo({ left: maxScroll, behavior: 'auto' });
                } else {
                    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                }
            } else {
                if (current >= maxScroll - 5) {
                    container.scrollTo({ left: 0, behavior: 'auto' });
                } else {
                    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                }
            }
        } else {
            // PRODUCT: không loop, chỉ cuộn trong khoảng [0, maxScroll]
            let target =
                direction === 'left'
                    ? current - scrollAmount
                    : current + scrollAmount;

            if (target < 0) target = 0;
            if (target > maxScroll) target = maxScroll;

            container.scrollTo({ left: target, behavior: 'smooth' });
        }
    };

    /**
     * Khởi tạo auto slide cho 1 slider
     */
    function initAutoSlide(id, time) {
        const container = document.getElementById(id);
        if (!container) return;

        const hero = isHeroSlider(container);
        const interval = typeof time === 'number' ? time : 5000;

        // Nếu slider không đủ rộng để cuộn thì khỏi auto slide
        if (container.scrollWidth <= container.clientWidth + 5) {
            return;
        }

        const start = () => {
            stop();
            autoSlideTimers[id] = setInterval(() => {
                // Hero dùng scrollSlider (có loop), product vẫn dùng logic mượt
                scrollSlider(id, 'right');
            }, interval);
        };

        const stop = () => {
            if (autoSlideTimers[id]) {
                clearInterval(autoSlideTimers[id]);
                autoSlideTimers[id] = null;
            }
        };

        // Pause khi hover container
        container.addEventListener('mouseenter', stop);
        container.addEventListener('mouseleave', start);

        // Pause khi hover/click nút trái/phải
        const wrapper = container.closest('.product-slider-wrapper') || container.parentElement;
        if (wrapper) {
            const buttons = wrapper.querySelectorAll('.slider-btn');
            buttons.forEach(btn => {
                btn.addEventListener('mouseenter', stop);
                btn.addEventListener('mouseleave', start);
                btn.addEventListener('click', () => {
                    stop();
                    setTimeout(start, 2000); // delay 2s sau click để người dùng nhìn
                });
            });
        }

        // Hero: luôn auto slide
        // Product: vẫn auto nhưng nếu bạn không thích thì comment mấy dòng start() phía dưới tại phần gọi
        start();
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Đo kích thước lần đầu
        measureAllSliders();

        // Auto slide
        initAutoSlide('main-banner-slider', 3500); // Hero

        // Các slider sản phẩm (nếu muốn tắt auto cho 1 cụm thì comment dòng tương ứng)
        initAutoSlide('slider-laptop', 5000);
        initAutoSlide('slider-pc', 6000);
        initAutoSlide('slider-monitor', 5000);
        initAutoSlide('slider-components', 5500);
        initAutoSlide('slider-gear', 5500);
        initAutoSlide('slider-accessories', 5000);

        // Re-measure khi resize
        window.addEventListener('resize', onResize);
    });
})();