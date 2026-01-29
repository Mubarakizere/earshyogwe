<?php

namespace App\NotificationChannels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toOneSignal($notifiable);

        if (empty($message)) {
            return;
        }

        $appId = config('services.onesignal.app_id');
        $restApiKey = config('services.onesignal.rest_api_key');

        if (!$appId || !$restApiKey) {
            Log::warning('OneSignal credentials not configured');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $restApiKey,
                'Content-Type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id' => $appId,
                'include_external_user_ids' => [(string) $notifiable->id],
                'headings' => ['en' => $message['title'] ?? config('app.name')],
                'contents' => ['en' => $message['body']],
                'url' => $message['url'] ?? null,
                'chrome_web_icon' => $message['icon'] ?? asset('images/logo.png'),
                'data' => $message['data'] ?? [],
            ]);

            if (!$response->successful()) {
                Log::error('OneSignal notification failed', [
                    'response' => $response->json(),
                    'user_id' => $notifiable->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('OneSignal notification exception', [
                'message' => $e->getMessage(),
                'user_id' => $notifiable->id,
            ]);
        }
    }
}
