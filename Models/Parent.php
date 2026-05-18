<?php

  require_once 'User.php';
  require_once 'Child.php';
  require_once 'Course.php';
  require_once 'Assignment.php';
  require_once 'Database.php';
  require_once 'Teacher.php';
  require_once 'Application.php';
  class Parents extends User
  {
   private $ParentId;
   private $UserId;
   private $PhoneNumber;
   private $Address;
   private $NotiPreferences; //json

   function AddChild ($data)
   {
     $child = new Child();
     $child->name = $data['name'];
     $child->DateofBirth = $data['DateOfBirth'];
     $child->Gender = $data['Gender'];
     $child->allergies = isset($data['allergies']) ? $data['allergies'] : null;
     $child->MedicalNotes = isset($data['MedicalNotes']) ? $data['MedicalNotes'] : null;
     $child->EmergencyContact = isset($data['EmergencyContact']) ? $data['EmergencyContact'] : null; 
     $child->ParentId = $this->ParentId;
     $child->EnrollmentStatus = "pending";

     $sql = "INSERT INTO Children (ParentID, Name, DOB, Gender, Allergies, MedicalNotes, EmergencyContact, EnrollmentStatus)
      Values (?,?,?,?,?,?,?,?)";

      $params = [$this->ParentId, $child->name, $child->DateofBirth, $child->Gender, $child->allergies, $child->MedicalNotes, $child->EmergencyContact, $child->EnrollmentStatus];
      $stmt = Database::getInstance()->query($sql, $params);
      if ($stmt && $stmt->rowCount() > 0) {
        return true;
     }
      return false;
   }

   function EnrollChild($data)
   {
        // Code to enroll child in course
   }

   function SubmitApplication($data)
   {
            $application = new Application();
            $application->ParentID = $this->ParentId;
            $application->ChildId = $data['ChildId'];
            $application->CourseId = $data['CourseId'];
            $application->Status = "pending";
            $application->SubmittedAt = date("Y-m-d H:i:s");
            $application->Documents = isset($data['Documents']) ? json_encode($data['Documents']) : null;
            // Code to save application to database
            $sql = "INSERT INTO Applications (ParentId, ChildId, CourseId, Status, SubmittedAt, Documents)
               VALUES (?,?,?,?,?,?)";
               $params = [$application->ParentID, $application->ChildId, $application->CourseId, $application->Status, $application->SubmittedAt, $application->Documents];
               $stmt = Database::getInstance()->query($sql, $params);
               if ($stmt && $stmt->rowCount() > 0) {
                 return true;
               }
               return false; 
   }

   function MakePayment($subId)
   {
        // Code to make payment
   }

   function RequestEnrollment($ParentId,$ChildId)
   {
        // Code to request enrollment for child
   }

   function SubmitAssignment($assignId,$data)
   {
        // Code to submit assignment
   }

   function SubmitExcuse ($childId,$sessionId,$reason)
   {
        // Code to submit excuse for absence
   }

   function SubmitRSVP($eventId,$response)
   {
        // Code to submit RSVP for event
   }

   function SignConstentForm ($tripId)
   {
        // Code to sign consent form for trip
   }

   function UpdateMedicalInfo ($childId,$data)
   {

   }
   
   function DownloadInVoice ($paymentId)
   {
        // Code to download invoice for payment
   }
   
   function Getprefrences()
   {
        // Code to get notification preferences
   }

   function SetPrefrences($prefs)
   {
        // Code to set notification preferences
   }
  }
?>