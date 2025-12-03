<?php

class ImgBB {
    public static function upload($fileTmpPath) {
        $apiKey = "YOUR_IMGBB_API_KEY"; // Thay API key

        $imageData = base64_encode(file_get_contents($fileTmpPath));

        $payload = [
            'key'   => $apiKey,
            'image' => $imageData
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.imgbb.com/1/upload',
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $payload,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($response, true);

        return $json['data']['url'] ?? false;
    }
}
