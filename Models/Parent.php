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
     $child = new Child(null, $this->ParentId, $data['DateOfBirth'], $data['Gender'], $data['allergies'] ?? null, $data['MedicalNotes'] ?? null, $data['EmergencyContact'] ?? null, 'Pending', $data['PhotoPath'] ?? null); 
     $sql = "INSERT INTO Child (parentID, name, dateOfBirth, gender, allergies, medicalNotes, emergencyContact, enrollmentStatus)
      VALUES (?,?,?,?,?,?,?,?)";
     $params = [$this->ParentId, $data['Name'], $data['DateOfBirth'], $data['Gender'], $data['allergies'] ?? null, $data['MedicalNotes'] ?? null, $data['EmergencyContact'] ?? null, 'Pending'];
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
        $courseSql = "SELECT * FROM Course WHERE courseID = ?";
        $course = Database::getInstance()->fetchOne($courseSql, [$courseId]);
        
        if (!$course) {
            return ['status' => 'error', 'message' => 'Course not found'];
        }
        
        // Check if course has available seats
        $enrollmentSql = "SELECT COUNT(*) as count FROM Enrollment WHERE courseID = ? AND status = 'Active'";
        $enrollment = Database::getInstance()->fetchOne($enrollmentSql, [$courseId]);
        
        if ($enrollment['count'] >= $course['maxCapacity']) {
            return ['status' => 'error', 'message' => 'Course is full', 'code' => 'COURSE_FULL'];
        }
        
        // Create enrollment
        $sql = "INSERT INTO Enrollment (childID, courseID, enrolledAt, status, isWaitlisted) 
                VALUES (?, ?, ?, ?, 0)";
        $params = [$childId, $courseId, date('Y-m-d H:i:s'), 'Active'];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Child enrolled successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Enrollment failed'];
   }

   function SubmitApplication($data)
   {
               $sql = "INSERT INTO Application (parentID, childID, status, reviewedAt, rejectionReason, documents)
               VALUES (?,?,?,?,?,?)";
               $params = [$this->ParentId, $data['ChildId'], 'Pending', null, null, isset($data['Documents']) ? json_encode($data['Documents']) : null];
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
        $subSql = "SELECT * FROM Subscription WHERE subscriptionID = ?";
        $subscription = Database::getInstance()->fetchOne($subSql, [$subId]);
        
        if (!$subscription) {
            return ['status' => 'error', 'message' => 'Subscription not found'];
        }
        
        // Prepare payment data
        $paymentData = [
            'amount' => $subscription['basePrice'],
            'subscriptionId' => $subId,
            'parentId' => $this->ParentId,
            'customerEmail' => $this->getEmail(),
            'customerPhone' => $this->PhoneNumber,
            'customerName' => $this->getFirstName() . ' ' . $this->getLastName(),
            'description' => $subscription['planName'] . ' subscription'
        ];
        
        // Process payment using default gateway
        $processor = PaymentProcessor::getInstance();
        return $processor->processPayment($processor->getDefaultGateway(), $paymentData);
   }

   function RequestEnrollment($ParentId, $ChildId, $CourseId)
   {
        $sql = "INSERT INTO Waitlist (parentID, childID, courseID, addedAt, status) 
                VALUES (?, ?, ?, ?, 'Waiting')";
        $params = [$ParentId, $ChildId, $CourseId, date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Waitlist request submitted'];
        }

        return ['status' => 'error', 'message' => 'Failed to submit waitlist request'];
   }

   function SubmitAssignment($assignId,$data)
   {
        $childId = $data['ChildId'];
        $submissionPath = $data['SubmissionPath'] ?? null;
        
        $sql = "INSERT INTO submission (assignmentID, childID, parentID, type, content, photoPath, submittedAt, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$assignId, $childId, $this->ParentId, 'Text', $submissionPath, null, date('Y-m-d H:i:s'), 'Submitted'];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Assignment submitted successfully'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to submit assignment'];
   }

   function SubmitExcuse ($childId,$sessionDate,$reason)
   {
        // Verify attendance record exists for the absent session
        $attSql = "SELECT * FROM Attendance WHERE childID = ? AND sessionDate = ? AND status = 'Absent'";
        $attendance = Database::getInstance()->fetchOne($attSql, [$childId, $sessionDate]);
        
        if (!$attendance) {
            return ['status' => 'error', 'message' => 'No absent record found for this session'];
        }
        
        $sql = "INSERT INTO Excuse (childID, parentID, sessionDate, reason, status, submittedAt) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$childId, $this->ParentId, $sessionDate, $reason, 'Pending', date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        
        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Excuse submitted for review'];
        }
        
        return ['status' => 'error', 'message' => 'Failed to submit excuse'];
   }

   function SubmitRSVP($eventId, $childId, $response)
   {
        $map = [
            'attending' => 'Confirmed',
            'not_attending' => 'Declined',
            'maybe' => 'Pending'
        ];

        if (!isset($map[$response])) {
            return ['status' => 'error', 'message' => 'Invalid RSVP response'];
        }

        $sql = "INSERT INTO EventRSVP (eventID, parentID, childID, response, respondedAt) 
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE response = VALUES(response), respondedAt = VALUES(respondedAt)";
        $params = [$eventId, $this->ParentId, $childId, $map[$response], date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'RSVP submitted successfully'];
        }

        return ['status' => 'error', 'message' => 'Failed to submit RSVP'];
   }

   function SignConstentForm ($tripId)
   {
        $sql = "UPDATE ConsentForm SET signedAt = ?, isSigned = 1 WHERE eventID = ? AND parentID = ?";
        $params = [date('Y-m-d H:i:s'), $tripId, $this->ParentId];
        $stmt = Database::getInstance()->query($sql, $params);

        if ($stmt && $stmt->rowCount() > 0) {
            return ['status' => 'success', 'message' => 'Consent form signed successfully'];
        }

        return ['status' => 'error', 'message' => 'Failed to sign consent form'];
   }

   function UpdateMedicalInfo ($childId,$data)
   {
        $sql = "UPDATE child SET allergies = ?, medicalNotes = ?, emergencyContact = ? 
                WHERE childID = ? AND parentID = ?";
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
        $sql = "SELECT * FROM Payment WHERE paymentID = ? AND parentID = ?";
        $payment = Database::getInstance()->fetchOne($sql, [$paymentId, $this->ParentId]);

        if (!$payment) {
            return ['status' => 'error', 'message' => 'Payment not found'];
        }

        if (!$payment['invoicePath'] || !file_exists($_SERVER['DOCUMENT_ROOT'] . $payment['invoicePath'])) {
            return ['status' => 'error', 'message' => 'Invoice not available'];
        }

        return [
            'status' => 'success',
            'invoicePath' => $payment['invoicePath'],
            'amount' => $payment['amount'],
            'paidAt' => $payment['paidAt']
        ];
   }
   
   function GetParentByName($name)
   {
        $sql = "SELECT p.* FROM Parent p
                INNER JOIN User u ON p.userID = u.userID
                WHERE u.firstName LIKE ? OR u.lastName LIKE ?
                LIMIT 1";
        $params = ["%{$name}%", "%{$name}%"];
        return Database::getInstance()->fetchOne($sql, $params);
   }

   function GetUserID()
   {
        return $this->UserId;
   }

   function GetId()
   {
        return $this->ParentId;
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