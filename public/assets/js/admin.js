// Chờ cho toàn bộ tài liệu được tải
document.addEventListener('DOMContentLoaded', function() {

    // Dòng này để kiểm tra xem file JS đã được tải đúng chưa
    console.log('admin.js đã tải thành công!'); 

    // 1. Tìm nút bấm
    const toggleButton = document.querySelector('.sidebar-toggle');

    // 2. Tìm trình bao bọc chính
    const appWrapper = document.querySelector('.app-wrapper');

    // 3. Kiểm tra xem cả hai có tồn tại không
    if (toggleButton && appWrapper) {

        // 4. Thêm sự kiện 'click' cho nút bấm
        toggleButton.addEventListener('click', function() {

            // 5. Thêm hoặc xóa class 'sidebar-collapsed'
            appWrapper.classList.toggle('sidebar-collapsed');
        });
    } else {
        // Báo lỗi nếu không tìm thấy nút hoặc wrapper
        console.error('Không tìm thấy nút .sidebar-toggle hoặc .app-wrapper');
    }
});