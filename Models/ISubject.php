<?php

interface ISubject
{
    public function Attach(IObserver $observer);
    public function Detach(IObserver $observer);
    public function Notify();
}