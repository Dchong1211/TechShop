<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
require_once __DIR__ . '/phpqrcode/qrlib.php';

class QR {
    public static function make($text, $filePath) {
        // Tạo folder nếu chưa có
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Tạo QR
        QRcode::png($text, $filePath, QR_ECLEVEL_L, 6);
        return $filePath;
    }
}
