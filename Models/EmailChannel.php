<?php

require_once 'INotificationChannel.php';

class EmailChannel implements INotificationChannel
{
    public function Send($recipient, $message)
    {
        // Implementation for sending email
        $to = $recipient;
        $subject = "Notification";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        
        $mailSent = mail($to, $subject, $message, $headers);
        return $mailSent;
    }
}
