<?php

interface INotificationChannel
{
    public function Send($recipient, $message);
}
