<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Get Fonnte API Token
     */
    public static function getToken(): string
    {
        return env('FONNTE_TOKEN', 'uPTfDMT8qieVQvC8YviR');
    }

    /**
     * Send WhatsApp Message via Fonnte API
     *
     * @param string $target (Phone number e.g. 089629615301 or 6289629615301)
     * @param string $message
     * @return bool
     */
    public static function sendNotification(string $target, string $message): bool
    {
        try {
            // Clean phone number to digits
            $target = preg_replace('/[^0-9]/', '', $target);
            if (empty($target)) {
                Log::warning("Fonnte WA skipped: target phone number is empty.");
                return false;
            }

            // Standardize Indonesian phone number to 62...
            if (str_starts_with($target, '0')) {
                $target = '62' . substr($target, 1);
            }

            $token = self::getToken();

            Log::info("Attempting Fonnte WA send to target {$target}...");

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->asForm()
                ->post('https://api.fonnte.com/send', [
                    'token' => $token,
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            $responseBody = $response->body();
            Log::info("Fonnte WA Result for {$target}: Status {$response->status()} | Response: {$responseBody}");

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("Fonnte API Exception for {$target}: " . $e->getMessage());
            return false;
        }
    }
}
