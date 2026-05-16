<?php
interface IpaymentStrategy
{
    function ProcessPayment( $amount )
    {
       // code
    }

    function HandleWebHook( $Payload )
    {

    }

    function GenerateReference ()
    {

    }

    function GetGatewayName()
    {

    }
    function ValidateCredentials()
    {
        
    }
}
?>