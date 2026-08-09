<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Exception;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : The email address to send the test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Attempting to send a test email to: {$email}");
        $this->info("Using Mailer: " . config('mail.default'));
        $this->info("Host: " . config('mail.mailers.smtp.host'));
        $this->info("Port: " . config('mail.mailers.smtp.port'));

        try {
            Mail::raw('This is a test email sent from the ' . config('app.name') . ' application to verify your SMTP configuration.', function (Message $message) use ($email) {
                $message->to($email)
                        ->subject('SMTP Test - ' . config('app.name'));
            });

            $this->info('✅ Test email sent successfully! Please check your inbox.');
        } catch (Exception $e) {
            $this->error('❌ Failed to send test email.');
            $this->error('Error Message: ' . $e->getMessage());
        }
    }
}
