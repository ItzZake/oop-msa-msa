<?php

  require_once 'Teacher.php';
  class Course
  {
   private $courseId;
   private $Name;
   private $Description;
   private $AgeMin;
   private $AgeMax;
   private $MaxCapacity;
   private $CurrentEnrollment;
   private $Price;
   private $Schedule; //json
   private $IsActive;

   function CheckSeats()
   {
    // Code to check if seats are available in course
   }

   function Isfull()
   {
    // Code to check if course is full
   }

   function IsEligible(childAge)
   {
    // Code to check if child is eligible for course based on age
   }

   function GetEnrolledChildren()
   {
    // Code to get list of children enrolled in course
   }

   function GetAssignedTeacher()
   {
    // Code to get teacher assigned to course
   }

   function GetAttendanceSessions()
   {
    // Code to get attendance sessions for course
   }

   function AddToWaitlist(childId)
   {
    // Code to add child to waitlist for course
   }
  }
?>