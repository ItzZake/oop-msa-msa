<?php
 
 require_once 'User.php';
 require_once 'Course.php';
 require_once 'Settings.php';
 require_once 'Database.php';
 require_once 'Teacher.php';
 class Admin extends User
 {
  private $AdminId;
  Private $UserId;

  function ApproveApplication($data)
  {
    $applicationId = $data['ApplicationId'];
    $reason = $data['Reason'] ?? 'Application approved';
    
    $sql = "UPDATE Applications SET Status = 'approved', ApprovedAt = ?, ApprovedByAdminId = ? WHERE ApplicationID = ?";
    $params = [date('Y-m-d H:i:s'), $this->AdminId, $applicationId];
    $stmt = Database::getInstance()->query($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        // Send notification to parent
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
    
    $sql = "UPDATE Applications SET Status = 'rejected', RejectedAt = ?, RejectionReason = ?, RejectedByAdminId = ? WHERE ApplicationID = ?";
    $params = [date('Y-m-d H:i:s'), $reason, $this->AdminId, $applicationId];
    $stmt = Database::getInstance()->query($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        // Send notification to parent
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
    
    // Ensure directory exists
    if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/reports')) {
        mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/reports', 0755, true);
    }
    
    switch($type) {
        case 'attendance':
            $sql = "SELECT c.Name, a.Status, a.SessionDate FROM AttendanceRecords a 
                    INNER JOIN Children c ON a.ChildId = c.ChildId
                    WHERE a.SessionDate BETWEEN ? AND ?";
            $params = [$filters['from'], $filters['to']];
            break;
        case 'payments':
            $sql = "SELECT p.PaymentID, p.Amount, p.Status, p.CreatedAt, u.Name FROM Payments p
                    INNER JOIN Users u ON p.ParentID = u.UserId
                    WHERE p.CreatedAt BETWEEN ? AND ?";
            $params = [$filters['from'], $filters['to']];
            break;
        case 'enrollments':
            $sql = "SELECT c.Name, co.Name as CourseName, e.Status, e.EnrolledAt FROM Enrollments e
                    INNER JOIN Children c ON e.ChildId = c.ChildId
                    INNER JOIN Courses co ON e.CourseId = co.CourseId
                    WHERE e.EnrolledAt BETWEEN ? AND ?";
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
    $sql = "UPDATE Teachers SET AssignedTimetable=? WHERE TeacherId=?";
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
    $sql = "UPDATE Teachers SET AssignedTimetable=? WHERE TeacherId=?";
    $params = [$jSON, $TeacherID];
    $stmt = Database::getInstance()->query($sql, $params);
    if ($stmt && $stmt->rowCount() > 0) {
      return true;
    }
    // Code to edit timetable
  }

  function CreateARRrule($data)
  {
    $sql = "INSERT INTO ARRules (AssetId, TriggerCondition, TargetAction, IsActive, CreatedAt) 
            VALUES (?, ?, ?, ?, ?)";
    $params = [
        $data['AssetId'],
        json_encode($data['TriggerCondition']),
        json_encode($data['TargetAction']),
        isset($data['IsActive']) ? (int)$data['IsActive'] : 1,
        date('Y-m-d H:i:s')
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
     $sql = "UPDATE Teachers SET AssignedCourses=CONCAT(IFNULL(AssignedCourses, ''), ?) WHERE TeacherId=?";
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
      $Name = $data['Name'];
      $Email = $data['Email'];
      $Password = password_hash($data['Password'], PASSWORD_DEFAULT);
      $Role = $data['Role'];
      $sql = "INSERT INTO Users (Name, Email, Password, Role) VALUES (?,?,?,?)";
      $params = [$Name, $Email, $Password, $Role];
      $stmt = Database::getInstance()->query($sql, $params);
      if ($stmt && $stmt->rowCount() > 0) {
        return true;
      }
    // Code to create staff profile
  }

  function ViewDashboard()
  {
    // Get enrollment count
    $enrollSql = "SELECT COUNT(*) as count FROM Enrollments WHERE Status = 'Active'";
    $enrollments = Database::getInstance()->fetchOne($enrollSql)['count'];
    
    // Get active courses
    $coursesSql = "SELECT COUNT(*) as count FROM Courses WHERE IsActive = 1";
    $courses = Database::getInstance()->fetchOne($coursesSql)['count'];
    
    // Get pending applications
    $appSql = "SELECT COUNT(*) as count FROM Applications WHERE Status = 'pending'";
    $pendingApps = Database::getInstance()->fetchOne($appSql)['count'];
    
    // Get revenue (completed payments)
    $revenueSql = "SELECT SUM(Amount) as total FROM Payments WHERE Status = 'Completed'";
    $revenue = Database::getInstance()->fetchOne($revenueSql)['total'] ?? 0;
    
    // Get attendance rate
    $attendanceSql = "SELECT COUNT(CASE WHEN Status = 'Present' THEN 1 END) as present,
                              COUNT(*) as total FROM AttendanceRecords";
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
    $sql = "UPDATE Flags SET Status = 'resolved', ResolvedAt = ?, ResolvedByAdminId = ?, ResolutionNotes = ? WHERE FlagID = ?";
    $params = [date('Y-m-d H:i:s'), $this->AdminId, $reason, $flagId];
    $stmt = Database::getInstance()->query($sql, $params);
    
    if ($stmt && $stmt->rowCount() > 0) {
        return ['status' => 'success', 'message' => 'Flag cleared successfully'];
    }
    
    return ['status' => 'error', 'message' => 'Failed to clear flag'];
  }
 }
?>