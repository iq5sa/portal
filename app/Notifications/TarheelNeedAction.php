<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TarheelNeedAction extends Notification
{
    use Queueable;
    private $student;

    /**
     * Create a new notification instance.
     *
     * @param $student
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $name = '';
        if ($this->student->first_name == null) {
            $name = $this->student->full_name . " " . $this->student->surname;
        } else {
            $name = $this->student->first_name . ' ' . $this->student->father_name . ' ' . $this->student->middle_name . ' ' . $this->student->last_name . ' ' . $this->student->surname;
        }
        return [
            'student' => $this->student,
            'message' => 'يجب ترقين قيد الطالب '.$name,
            'icon' => 'fa fa-graduation-cap',
            'color' => 'text-danger'
        ];
    }
}
