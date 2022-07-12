<?php

namespace App\Console\Commands;

use App\EmailRecipient;
use App\JobRequest;
use App\Mail\FormJobConfirmation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class HourlyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hour:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to confirm job request to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = JobRequest::all()->where('emailSent','=',0);
        $to_emails = EmailRecipient::all();
        foreach ($user as $a)
        {
            foreach($to_emails as $to_email){
                Mail::to($to_email)->send(new FormJobConfirmation($a,$to_email));
            }
            $a->emailSent = 1;
            $a->update();
        }
        $this->info('Hourly Update has been send successfully');
    }
}
