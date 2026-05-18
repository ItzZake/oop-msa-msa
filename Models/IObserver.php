<?php

interface IObserver
{
    public function Update(ISubject $subject);
}
