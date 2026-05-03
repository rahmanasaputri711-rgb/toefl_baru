<?php
namespace App\Services;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Kirim notifikasi lengkap:
     * 1. In-app (tabel notifikasi)
     * 2. FCM Push Notification (jika fcm_token tersedia)
     */
    public function kirimNotifikasi(
        User   $user,
        string $judul,
        string $pesan,
        string $tipe       = 'info',   // info|sukses|warning|danger
        string $deepLink   = '/dashboard',
        bool   $penting    = false
    ): void {
        // 1. Simpan ke in-app notification
        Notifikasi::create([
            'user_id'     => $user->id,
            'judul'       => $judul,
            'pesan'       => $pesan,
            'tipe'        => $tipe,
            'is_important'=> $penting,
            'is_read'     => false,
        ]);

        // 2. FCM Push Notification
        if ($user->fcm_token) {
            $this->kirimFCM($user->fcm_token, $judul, $pesan, $deepLink);
        }
    }

    /**
     * Kirim ke Firebase Cloud Messaging via HTTP v1 API
     * Tanpa package kreait (langsung HTTP) agar tidak ada dependency baru.
     */
    private function kirimFCM(string $token, string $judul, string $pesan, string $deepLink): void
    {
        $projectId = config('services.firebase.project_id');
        if (!$projectId) return;

        $serviceAccountPath = storage_path('app/firebase/service-account.json');
        if (!file_exists($serviceAccountPath)) {
            Log::warning('FCM: service-account.json tidak ditemukan.');
            return;
        }

        try {
            // Buat access token dari service account
            $accessToken = $this->getFirebaseAccessToken($serviceAccountPath);
            if (!$accessToken) return;

            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $judul,
                        'body'  => $pesan,
                    ],
                    'webpush' => [
                        'notification' => [
                            'title'    => $judul,
                            'body'     => $pesan,
                            'icon'     => '/logo.png',
                            'click_action' => url($deepLink),
                        ],
                        'fcm_options' => [
                            'link' => url($deepLink),
                        ],
                    ],
                    'data' => [
                        'deep_link' => $deepLink,
                        'type'      => 'toefl_notification',
                    ],
                ],
            ];

            $ch = curl_init("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_TIMEOUT        => 10,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::warning('FCM gagal: ' . $httpCode . ' - ' . $response);
            }
        } catch (\Throwable $e) {
            Log::error('FCM exception: ' . $e->getMessage());
        }
    }

    /**
     * Generate short-lived access token dari service account JSON
     * menggunakan JWT (tidak perlu library eksternal).
     */
    private function getFirebaseAccessToken(string $serviceAccountPath): ?string
    {
        $sa = json_decode(file_get_contents($serviceAccountPath), true);
        if (!$sa) return null;

        $now = time();
        $header  = base64_encode(json_encode(['alg'=>'RS256','typ'=>'JWT']));
        $payload = base64_encode(json_encode([
            'iss'   => $sa['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));

        $unsignedJwt = "$header.$payload";
        openssl_sign($unsignedJwt, $signature, $sa['private_key'], OPENSSL_ALGO_SHA256);
        $jwt = "$unsignedJwt." . base64_encode($signature);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]),
            CURLOPT_TIMEOUT => 10,
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $res['access_token'] ?? null;
    }
}
