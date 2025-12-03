<?php

class ImgBB {
    private static $apiKey = "0275912d6d120a13546bea4af61d67e2";

    public static function upload($fileTmpPath) {
        if (!file_exists($fileTmpPath)) {
            return false;
        }

        $imageData = base64_encode(file_get_contents($fileTmpPath));

        $payload = [
            'key'   => self::$apiKey,
            'image' => $imageData
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://api.imgbb.com/1/upload",
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            curl_close($curl);
            return false;
        }

        curl_close($curl);

        $json = json_decode($response, true);

        // Trả về link ảnh hoặc false nếu thất bại
        return $json['data']['url'] ?? false;
    }
}
