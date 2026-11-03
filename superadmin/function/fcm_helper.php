<?php
class FCMHelper {
    private $serviceAccount;
    private $accessToken;

    public function __construct($serviceAccountPath = '') {
        // 1. Load context from .env
        $envPath = __DIR__ . '/../../.env';
        if (file_exists($envPath)) {
            $this->loadEnv($envPath);
        }

        if (getenv('FIREBASE_PROJECT_ID')) {
            $this->serviceAccount = [
                'project_id'   => getenv('FIREBASE_PROJECT_ID'),
                'client_email' => getenv('FIREBASE_CLIENT_EMAIL'),
                'private_key'  => getenv('FIREBASE_PRIVATE_KEY')
            ];
        } elseif ($serviceAccountPath && file_exists($serviceAccountPath)) {
            $json = file_get_contents($serviceAccountPath);
            $this->serviceAccount = json_decode($json, true);
        }

        if (!$this->serviceAccount) {
            throw new Exception("Firebase credentials not found. Please setup .env or provide service-account.json");
        }
    }

    private function loadEnv($path) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remove surrounding quotes
            $value = preg_replace('/^["\'](.*)["\']$/', '$1', $value);
            
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
        }
    }

    private function base64UrlEncode($data) {
        return rtrim(str_replace(['+', '/'], ['-', '_'], base64_encode($data)), '=');
    }

    private function getAccessToken() {
        if ($this->accessToken) return $this->accessToken;

        $now = time();
        $payload = [
            'iss'   => $this->serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $now + 3600,
            'iat'   => $now - 300 
        ];

        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        
        $headerJSON = json_encode($header);
        $payloadJSON = json_encode($payload, JSON_UNESCAPED_SLASHES);
        
        $headerEncoded  = $this->base64UrlEncode($headerJSON);
        $payloadEncoded = $this->base64UrlEncode($payloadJSON);
        
        $dataToSign = $headerEncoded . "." . $payloadEncoded;
        
        $privateKey = $this->serviceAccount['private_key'];
        
        // Robust cleaning
        $privateKey = trim($privateKey, " \t\n\r\0\x0B\"'");
        $privateKey = str_replace(['\n', "\\n"], "\n", $privateKey);

        if (strpos($privateKey, "-----BEGIN") !== false) {
            if (preg_match('/-----BEGIN [^-]+-----(.*)-----END [^-]+-----/s', $privateKey, $matches)) {
                $base64 = trim($matches[1]);
                $base64 = preg_replace('/\s+/', '', $base64);
                $chunks = str_split($base64, 64);
                $privateKey = "-----BEGIN PRIVATE KEY-----\n" . implode("\n", $chunks) . "\n-----END PRIVATE KEY-----";
            }
        }

        $res = openssl_pkey_get_private($privateKey);
        if (!$res) {
            // Last effort
            $cleanBase64 = preg_replace('/---.*---|\\s+/', '', $privateKey);
            $chunks = str_split($cleanBase64, 64);
            $formattedKey = "-----BEGIN PRIVATE KEY-----\n" . implode("\n", $chunks) . "\n-----END PRIVATE KEY-----";
            $res = openssl_pkey_get_private($formattedKey);
            
            if (!$res) {
                throw new Exception("Invalid Private Key format. OpenSSL could not parse the key.");
            }
        }
        
        $signature = '';
        if (!openssl_sign($dataToSign, $signature, $res, "SHA256")) {
            throw new Exception("Local Signing Failed: " . openssl_error_string());
        }
        if (is_resource($res)) openssl_free_key($res);

        $jwt = $dataToSign . "." . $this->base64UrlEncode($signature);

        $postData = 'grant_type=' . urlencode('urn:ietf:params:oauth:grant-type:jwt-bearer') . '&assertion=' . urlencode($jwt);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $result = curl_exec($ch);
        $data = json_decode($result, true);
        curl_close($ch);

        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
            return $this->accessToken;
        }

        throw new Exception("Google Token Auth Error: " . ($data['error_description'] ?? ($data['error'] ?? $result)));
    }

    public function sendNotification($token, $title, $body, $link = '', $image = '') {
        try {
            $accessToken = $this->getAccessToken();
            $projectId = $this->serviceAccount['project_id'];

            $logoUrl = defined('SITE_URL') ? SITE_URL . 'assets/images/favicons/android-chrome-192x192.png' : 'https://a2prealtech.com/assets/images/favicons/android-chrome-192x192.png';
            $notificationIcon = !empty($image) ? $image : $logoUrl;

            $message = [
                'message' => [
                    'token' => $token,
                    'data' => [
                        'title' => (string)$title,
                        'body'  => (string)$body,
                        'link'  => (string)$link,
                        'image' => (string)$image
                    ],
                    'notification' => [
                        'title' => (string)$title,
                        'body'  => (string)$body,
                        'image' => (string)$image
                    ],
                    'webpush' => [
                        'notification' => [
                            'icon'  => $notificationIcon,
                            'badge' => $notificationIcon,
                            'requireInteraction' => true,
                        ],
                        'fcm_options' => [
                            'link' => (string)$link
                        ]
                    ]
                ]
            ];

            $ch = curl_init("https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

            $result = curl_exec($ch);
            curl_close($ch);
            
            $resData = json_decode($result, true);
            $success = isset($resData['name']);

            return [
                'success' => $success,
                'response' => $resData ?: ['raw' => $result]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'response' => ['error' => ['message' => $e->getMessage()]]
            ];
        }
    }
}
?>
