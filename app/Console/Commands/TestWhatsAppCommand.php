<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppNotificationService;

class TestWhatsAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'whatsapp:test {phone : Phone number to test}';

    /**
     * The console description of the command.
     */
    protected $description = 'Test WhatsApp notification functionality';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppNotificationService $whatsappService)
    {
        $phone = $this->argument('phone');
        
        $this->info("Testing WhatsApp notification to: {$phone}");
        $this->newLine();
        
        $success = $whatsappService->testConnection($phone);
        
        if ($success) {
            $this->info('✅ WhatsApp test notification sent successfully!');
        } else {
            $this->error('❌ Failed to send WhatsApp test notification!');
        }
        
        $this->newLine();
        $this->line('Check your WhatsApp to confirm delivery.');
        
        return $success ? 0 : 1;
    }
}