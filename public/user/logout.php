<?php
// public/user/logout.php

session_start();
session_unset();
session_destroy();

// Chuyển hướng về trang chủ của user
// (Sử dụng đường dẫn tuyệt đối dựa trên <base href>)
header('Location: /TechShop/public/user/index.php');
exit;
?>