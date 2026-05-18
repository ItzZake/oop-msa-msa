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

   public function AddChild ($data)
   {
     $child = new Child(null, $this->ParentId, $data['DateOfBirth'], $data['Gender'], $data['allergies'] ?? null, $data['MedicalNotes'] ?? null, $data['EmergencyContact'] ?? null, 'pending', $data['PhotoPath'] ?? null); 
     $sql = "INSERT INTO Children (ParentID, Name, DOB, Gender, Allergies, MedicalNotes, EmergencyContact, EnrollmentStatus)
      Values (?,?,?,?,?,?,?,?)";
     $params = [$this->ParentId, $data['Name'], $data['DateOfBirth'], $data['Gender'], $data['allergies'] ?? null, $data['MedicalNotes'] ?? null, $data['EmergencyContact'] ?? null, 'pending'];
      $stmt = Database::getInstance()->query($sql, $params);
      if ($stmt && $stmt->rowCount() > 0) {
        return true;
     }
      return false;
   }

   function EnrollChild($data)
   {
        $childId = $data['ChildId'];
        $courseId = $data['CourseId'];
        
        // Verify course exists and has availability
        $courseSql = "SELECT * FROM Courses WHERE CourseId = ?";
        $course = Database::getInstance()->fetchOne($courseSql, [$courseId]);
        
        if (!$course) {
            return ['status' => 'error', 'message' => 'Course not found'];
        }
        
        // Check if course has available seats
        $enrollmentSql = "SELECT COUNT(*) as count FROM Enrollments WHERE CourseId = ? AND Status = 'Active'";
        $enrollment = Database::getInstance()->fetchOne($enrollmentSql, [$courseId]);
        
        if ($enrollment['count'] >= $course['MaxCapacity']) {
            return ['status' => 'error', 'message' => 'Course is full', 'code' => 'COURSE_FULL'];
        }
        
        // Create enrollment
        $sql = "INSERT INTO Enrollments (ChildId, CourseId, ParentId, Status, EnrolledAt) 
                VALUES (?, ?, ?, ?, ?)";
        $params = [$childId, $courseId, $this->ParentId, 'Active', date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Child enrolled successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Enrollment failed'];
   }

   function SubmitApplication($data)
   {
            $application = new Application(null, $data['ChildId'], $this->ParentId, null, 'pending', null, date('Y-m-d H:i:s'), null, isset($data['Documents']) ? json_encode($data['Documents']) : null);
            $sql = "INSERT INTO Applications (ParentId, ChildId, CourseId, Status, SubmittedAt, Documents)
               VALUES (?,?,?,?,?,?)";
               $params = [$this->ParentId, $data['ChildId'], $data['CourseId'], 'pending', date('Y-m-d H:i:s'), isset($data['Documents']) ? json_encode($data['Documents']) : null];
               $stmt = Database::getInstance()->query($sql, $params);
               if ($stmt && $stmt->rowCount() > 0) {
                 return true;
               }
               return false; 
   }

   function MakePayment($subId)
   {
        require_once 'PaymentProcessor.php';
        
        // Get subscription details
        $subSql = "SELECT * FROM Subscriptions WHERE SubscriptionID = ?";
        $subscription = Database::getInstance()->fetchOne($subSql, [$subId]);
        
        if (!$subscription) {
            return ['status' => 'error', 'message' => 'Subscription not found'];
        }
        
        // Prepare payment data
        $paymentData = [
            'amount' => $subscription['BasePrice'],
            'subscriptionId' => $subId,
            'parentId' => $this->ParentId,
            'customerEmail' => $this->getEmail(),
            'customerPhone' => $this->PhoneNumber,
            'customerName' => $this->getFirstName() . ' ' . $this->getLastName(),
            'description' => $subscription['PlanName'] . ' subscription'
        ];
        
        // Process payment using default gateway
        $processor = PaymentProcessor::getInstance();
        return $processor->processPayment($processor->getDefaultGateway(), $paymentData);
   }

   function RequestEnrollment($ParentId,$ChildId)
   {
        $sql = "INSERT INTO EnrollmentRequests (ParentId, ChildId, Status, RequestedAt) 
                VALUES (?, ?, ?, ?)";
        $params = [$ParentId, $ChildId, 'pending', date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Enrollment request submitted'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to submit enrollment request'];
   }

   function SubmitAssignment($assignId,$data)
   {
        $childId = $data['ChildId'];
        $submissionPath = $data['SubmissionPath'] ?? null;
        
        $sql = "INSERT INTO Submissions (AssignmentID, ChildId, SubmissionPath, Status, SubmittedAt) 
                VALUES (?, ?, ?, ?, ?)";
        $params = [$assignId, $childId, $submissionPath, 'submitted', date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Assignment submitted successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to submit assignment'];
   }

   function SubmitExcuse ($childId,$sessionId,$reason)
   {
        // Verify attendance record exists
        $attSql = "SELECT * FROM AttendanceRecords WHERE ChildId = ? AND SessionId = ? AND Status = 'Absent'";
        $attendance = Database::getInstance()->fetchOne($attSql, [$childId, $sessionId]);
        
        if (!$attendance) {
            return ['status' => 'error', 'message' => 'No absent record found for this session'];
        }
        
        $sql = "INSERT INTO Excuses (ChildId, SessionId, Reason, Status, SubmittedAt, ParentId) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$childId, $sessionId, $reason, 'pending', date('Y-m-d H:i:s'), $this->ParentId];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Excuse submitted for review'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to submit excuse'];
   }

   function SubmitRSVP($eventId,$response)
   {
        // Validate response value
        if (!in_array($response, ['attending', 'not_attending', 'maybe'])) {
            return ['status' => 'error', 'message' => 'Invalid RSVP response'];
        }
        
        $sql = "INSERT INTO RSVPs (EventId, ParentId, Response, SubmittedAt) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE Response = VALUES(Response), SubmittedAt = VALUES(SubmittedAt)";
        $params = [$eventId, $this->ParentId, $response, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'RSVP submitted successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to submit RSVP'];
   }

   function SignConstentForm ($tripId)
   {
        $sql = "UPDATE ConsentForms SET SignedAt = ?, SignedByParentId = ?, Status = 'signed' 
                WHERE EventId = ? AND ParentId = ?";
        $params = [date('Y-m-d H:i:s'), $this->ParentId, $tripId, $this->ParentId];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Consent form signed successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to sign consent form'];
   }

   function UpdateMedicalInfo ($childId,$data)
   {
        $sql = "UPDATE Children SET Allergies = ?, MedicalNotes = ?, EmergencyContact = ? 
                WHERE ChildId = ? AND ParentId = ?";
        $params = [
            $data['allergies'] ?? null,
            $data['medicalNotes'] ?? null,
            $data['emergencyContact'] ?? null,
            $childId,
            $this->ParentId
        ];
        
        $stmt = Database::getInstance()->query($sql, $params);
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Medical information updated'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to update medical information'];
   }
   
   function DownloadInVoice ($paymentId)
   {
        $sql = "SELECT * FROM Payments WHERE PaymentID = ? AND ParentID = ?";
        $payment = Database::getInstance()->fetchOne($sql, [$paymentId, $this->ParentId]);
        
        if (!$payment) {
            return ['status' => 'error', 'message' => 'Payment not found'];
        }
        
        // Check if invoice exists
        if (!$payment['InvoicePath'] || !file_exists($_SERVER['DOCUMENT_ROOT'] . $payment['InvoicePath'])) {
            return ['status' => 'error', 'message' => 'Invoice not available'];
        }
        
        return [
            'status' => 'success',
            'invoicePath' => $payment['InvoicePath'],
            'amount' => $payment['Amount'],
            'paidAt' => $payment['PaidAt']
        ];
   }
   
   function Getprefrences()
   {
        require_once 'Notifiable.php';
        $notifiable = new Notifiable($this->UserId);
        return $notifiable->GetPrefrences();
   }

   function SetPrefrences($prefs)
   {
        require_once 'Notifiable.php';
        $notifiable = new Notifiable($this->UserId);
        return $notifiable->SetPrefrences($prefs);
   }
  }
?>