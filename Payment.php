<?php
 class Payment
 {
   private $PaymentId;
   Private $SubscriptionId;
   private $Amount;
   private $parentId;
   private $Gateway;
   private $GatewayTXId;
   private $Status; //Pending, Completed, Failed
   private $paidAt;
   private $InvoicePath;
   private $LineItems; 

   function ProcessWebHook($Payload)
   {
     // Code to process payment gateway webhook payload and update payment status accordingly
   }

   function MarkPaid()
   {
        // Code to mark payment as completed
   }

   function MarkFailed()
   {
        // Code to mark payment as failed
   }

   function GeneratePdf()
   {
        // Code to generate PDF invoice
   }

   function AddLineItem($Description, $Amount)
   {
        // Code to add line item to payment
   }

   function GetInvoice()
   {
        // Code to retrieve invoice details for payment
   }
 }
?>