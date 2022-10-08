<?php

namespace App\Services;
use Mail;
use Log;

class MailSender {

    public function send($subject, $body, $to, $from = ['test@gmail.com', 'Laravel Exam'], $wholesalerID = NULL, $attachFiles = NULL) {

        try {
            Mail::send([], [], function ($message) use ($subject, $from, $to, $body, $attachFiles) {
                $message->to($to)
                    ->subject($subject)
                    ->setBody($body, 'text/html');
                $message->from($from[0], $from[1]);

                if ($attachFiles) {
                    if (is_array($attachFiles)) {
                        foreach ($attachFiles as $files) {
                            $message->attach($files);
                        }
                    } else {
                        $message->attach($attachFiles);
                    }
                }
            });
            Log::info("[Email Sender] An email has been sent to {$to}");
            return true;

        } catch (\Exception $e) {
            Log::error("[Email Sender] Unable to Send Email: {$e->getMessage()}");
            return false;
        }
    }

}
