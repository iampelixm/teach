<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentLessomReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $student)
    {
        $this->student=$student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.reminder.student.lesson');
    }
}
