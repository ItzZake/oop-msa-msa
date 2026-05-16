<?php

  require_once 'User.php';
  
  class Parent extends User
  {
   Private $ParentId;
   Private $UserId;
   private $PhoneNumber;
   Private $Address;
   private $NotiPreferences; //json

   function AddChild (data)
   {
        // Code to add child
   }

   function EnrollChild(courseId, childId)
   {
        // Code to enroll child in course
   }

   function SubmitApplication(data)
   {
        // Code to submit application
   }

   function MakePayment(subId)
   {
        // Code to make payment
   }

   function RequestEnrollment(ParentId,ChildId)
   {
        // Code to request enrollment for child
   }

   function SubmitAssignment(assignId,data)
   {
        // Code to submit assignment
   }

   function SubmitExcuse (childId,sessionId,reason)
   {
        // Code to submit excuse for absence
   }

   function SubmitRSVP(eventId,response)
   {
        // Code to submit RSVP for event
   }

   function SignConstentForm (tripId)
   {
        // Code to sign consent form for trip
   }

   function UpdateMedicalInfo (childId,data)
   {
        // Code to update medical information for child
   }
   
   function DownloadInVoice (paymentId)
   {
        // Code to download invoice for payment
   }
   
   function Getprefrences()
   {
        // Code to get notification preferences
   }

   function SetPrefrences(prefs)
   {
        // Code to set notification preferences
   }
  }
?>