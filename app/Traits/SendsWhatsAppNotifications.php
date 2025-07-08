<?php

namespace App\Traits;

use App\Services\WhatsAppNotificationService;

trait SendsWhatsAppNotifications
{
    protected function sendWhatsAppNotification($model, string $eventType): bool
    {
        try {
            $whatsappService = app(WhatsAppNotificationService::class);
            return $whatsappService->sendNotification($model, $eventType);
        } catch (\Exception $e) {
            return false;
        }
    }
}