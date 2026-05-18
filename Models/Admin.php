<?php
 
 require_once 'User.php';
 require_once 'Course.php';
 require_once 'Settings.php';
 require_once 'Database.php';
 require_once 'Teacher.php';
 require_once 'Event.php';
 class Admin extends User
 {
  private $AdminId;
  Private $UserId;

  function ApproveApplication($data)
  {
    $applicationId = $data['ApplicationId'];
    $reason = $data['Reason'] ?? 'Application approved';
    
    $sql = "UPDATE Application SET status = 'Approved', reviewedAt = ?, rejectionReason = NULL WHERE applicationID = ?";
    $params = [date('Y-m-d H:i:s'), $applicationId];
    $stmt = Database::getInstance()->query($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        require_once 'NotificationManager.php';
        $manager = NotificationManager::getInstance();
        $manager->NotifyUser($data['ParentId'], "Your application has been approved");
        
        return ['status' => 'success', 'message' => 'Application approved successfully'];
    }
    
    return ['status' => 'error', 'message' => 'Failed to approve application'];
  }

  function RejectApplication($data)
  {
    $applicationId = $data['ApplicationId'];
    $reason = $data['Reason'] ?? 'Application rejected';
    
    $sql = "UPDATE Application SET status = 'Rejected', reviewedAt = ?, rejectionReason = ? WHERE applicationID = ?";
    $params = [date('Y-m-d H:i:s'), $reason, $applicationId];
    $stmt = Database::getInstance()->query($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        require_once 'NotificationManager.php';
        $manager = NotificationManager::getInstance();
        $manager->NotifyUser($data['ParentId'], "Your application was rejected. Reason: {$reason}");
        
        return ['status' => 'success', 'message' => 'Application rejected successfully'];
    }
    
    return ['status' => 'error', 'message' => 'Failed to reject application'];
  }

  function CreateCourse($data)
  {
    $Course = new Course();
    $Course->Create($data);
  }

  function EditCourse($courseId, $data)
  {
    $Course = new Course();
    $Course->Edit($courseId, $data);
    // Code to edit course
  }

  function EditSettings($data)
  {
    $Settings = new Settings();
    $Settings->Edit($data);
    // Code to edit settings
  }

  function ExportReport($type,$filters)
  {
    $filename = "report_" . $type . "_" . date('Y-m-d_H-i-s') . ".csv";
    $filepath = "/uploads/reports/" . $filename;
    
    if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/reports')) {
        mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/reports', 0755, true);
    }
    
    switch($type) {
        case 'attendance':
            $sql = "SELECT c.name AS Name, a.status AS Status, a.sessionDate AS SessionDate FROM Attendance a 
                    INNER JOIN Child c ON a.childID = c.childID
                    WHERE a.sessionDate BETWEEN ? AND ?";
            $params = [$filters['from'], $filters['to']];
            break;
        case 'payments':
            $sql = "SELECT p.paymentID AS PaymentID, p.amount AS Amount, p.status AS Status, p.paidAt AS PaidAt, CONCAT(u.firstname, ' ', u.Lastname) AS Name FROM Payment p
                    INNER JOIN Parent par ON p.parentID = par.parentID
                    INNER JOIN User u ON par.userID = u.userID
                    WHERE p.paidAt BETWEEN ? AND ?";
            $params = [$filters['from'], $filters['to']];
            break;
        case 'enrollments':
            $sql = "SELECT c.name AS Name, co.name as CourseName, e.status AS Status, e.enrolledAt AS EnrolledAt FROM Enrollment e
                    INNER JOIN Child c ON e.childID = c.childID
                    INNER JOIN Course co ON e.courseID = co.courseID
                    WHERE e.enrolledAt BETWEEN ? AND ?";
            $params = [$filters['from'], $filters['to']];
            break;
        default:
            return ['status' => 'error', 'message' => 'Invalid report type'];
    }
    
    $data = Database::getInstance()->fetchAll($sql, $params);
    
    if (!empty($data)) {
        $file = fopen($_SERVER['DOCUMENT_ROOT'] . $filepath, 'w');
        
        // Write header
        fputcsv($file, array_keys($data[0]));
        
        // Write data
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
        
        return ['status' => 'success', 'message' => 'Report generated', 'filepath' => $filepath];
    }
    
    return ['status' => 'error', 'message' => 'No data found for report'];
  }

  function CreateTimeTable($data, $TeacherId)
  {
    $jSON = json_encode($data);
    $sql = "UPDATE Teacher SET Assignedtimetable=? WHERE teacherID=?";
    $params = [$jSON, $TeacherId];
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
      return true;
    }
    // Code to create timetable
  }

  function EditTimeTable($TeacherID, $data)
  {
    $jSON = json_encode($data);
    $sql = "UPDATE Teacher SET Assignedtimetable=? WHERE teacherID=?";
    $params = [$jSON, $TeacherID];
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
      return true;
    }
    // Code to edit timetable
  }

  function CreateARRrule($data)
  {
    $sql = "INSERT INTO ARRule (courseID, assetID, object1, object2, object3, displayName, description, confidenceThreshold, isActive, createdBy) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [
        $data['courseID'],
        $data['assetID'],
        $data['object1'],
        $data['object2'],
        $data['object3'] ?? null,
        $data['displayName'],
        $data['description'] ?? null,
        $data['confidenceThreshold'] ?? 0.8,
        isset($data['isActive']) ? (int)$data['isActive'] : 1,
        $this->AdminId
    ];
    
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
        return ['status' => 'success', 'message' => 'AR Rule created successfully'];
    }
    
    return ['status' => 'error', 'message' => 'Failed to create AR Rule'];
  }

  function AssignTeacher($courseId,$teacherId,$data)
  {
    $Course = new Course();
    $Course->Edit($courseId, $data);
     $sql = "UPDATE Teacher SET AssignedCourses=CONCAT(IFNULL(AssignedCourses, ''), ?) WHERE teacherID=?";
     $params = [','.$courseId, $teacherId];
     $stmt = Database::getInstance()->query($sql, $params);
     if ($stmt && $stmt->rowCount() > 0) {
       return true;
    }
    // Code to assign teacher to course
  }

  function CreateEvent($data)
  {
    $Event = new Event();
    $Event->Publish($data);
    // Code to create event
  }

  function CreateStaffProfile($data)
  {
      $firstName = $data['FirstName'] ?? ($data['Name'] ?? '');
      $lastName = $data['LastName'] ?? '';
      $Email = $data['Email'];
      $Password = password_hash($data['Password'], PASSWORD_DEFAULT);
      $Role = $data['Role'];
      $sql = "INSERT INTO User (firstname, Lastname, email, passwordHash, Role) VALUES (?,?,?,?,?)";
      $params = [$firstName, $lastName, $Email, $Password, $Role];
      $stmt = Database::getInstance()->query($sql, $params);
      if ($stmt && $stmt->rowCount() > 0) {
        return true;
      }
    // Code to create staff profile
  }

  function ViewDashboard()
  {
    $enrollSql = "SELECT COUNT(*) as count FROM Enrollment WHERE status = 'Active'";
    $enrollments = Database::getInstance()->fetchOne($enrollSql)['count'];
    
    $coursesSql = "SELECT COUNT(*) as count FROM Course WHERE isActive = 1";
    $courses = Database::getInstance()->fetchOne($coursesSql)['count'];
    
    $appSql = "SELECT COUNT(*) as count FROM Application WHERE status = 'Pending'";
    $pendingApps = Database::getInstance()->fetchOne($appSql)['count'];
    
    $revenueSql = "SELECT SUM(amount) as total FROM Payment WHERE status = 'Paid'";
    $revenue = Database::getInstance()->fetchOne($revenueSql)['total'] ?? 0;
    
    $attendanceSql = "SELECT COUNT(CASE WHEN status = 'Present' THEN 1 END) as present,
                              COUNT(*) as total FROM Attendance";
    $attendance = Database::getInstance()->fetchOne($attendanceSql);
    $attendanceRate = $attendance['total'] > 0 ? ($attendance['present'] / $attendance['total']) * 100 : 0;
    
    return [
        'status' => 'success',
        'data' => [
            'activeEnrollments' => $enrollments,
            'activeCourses' => $courses,
            'pendingApplications' => $pendingApps,
            'totalRevenue' => $revenue,
            'attendanceRate' => round($attendanceRate, 2)
        ]
    ];
  }

  function ClearFlag($flagId,$reason)
  {
    $sql = "UPDATE Flag SET isActive = 0, clearedAt = ?, clearedBy = ?, clearReason = ? WHERE flagID = ?";
    $params = [date('Y-m-d H:i:s'), $this->AdminId, $reason, $flagId];
    $stmt = Database::getInstance()->query($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        return ['status' => 'success', 'message' => 'Flag cleared successfully'];
    }
    
    return ['status' => 'error', 'message' => 'Failed to clear flag'];
  }
 }
?>