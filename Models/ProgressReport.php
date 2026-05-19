<?php
require_once 'Database.php';
 class ProgressReport
 {
    private $ReportId;
    private $ChildId;
    private $TeacherId;
    private $Period;
    private $Observation;
    private $SkillRating;
    private $Status;    
    private $CreatedAt;
    private $PublishedAt;

    function SubmitReport($data)
    {
        $this->ChildId = $data['ChildId'];
        $this->TeacherId = $data['TeacherId'];
        $this->Period = $data['Period'];
        $this->Observation = $data['Observation'] ?? null;
        $this->SkillRating = json_encode($data['SkillRating'] ?? []);
        $this->Status = 'draft';
        $this->CreatedAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO progressreport (childID, teacherID, period, observation, skillRatings, status, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [$this->ChildId, $this->TeacherId, $this->Period, $this->Observation, $this->SkillRating, $this->Status, $this->CreatedAt];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function PublishReport($AdminId)
    {
        $this->PublishedAt = date('Y-m-d H:i:s');
        $sql = "UPDATE progressreport SET status = 'Published', publishedAt = ?, ReviewedByAdminId = ? WHERE reportID = ?";
        $params = [$this->PublishedAt, $AdminId, $this->ReportId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function ExportPDF()
    {
        $sql = "SELECT * FROM progressreport WHERE reportID = ?";
        $report = Database::getInstance()->fetchOne($sql, [$this->ReportId]);
        if (!$report) {
            return ['status' => 'error', 'message' => 'Progress report not found'];
        }

        $fileName = 'progress_report_' . $this->ReportId . '_' . date('YmdHis') . '.pdf';
        $filePath = '/uploads/progress_reports/' . $fileName;
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/progress_reports')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads/progress_reports', 0755, true);
        }

        $content = "Progress Report ID: {$this->ReportId}\n";
        $content .= "Child ID: {$report['ChildId']}\n";
        $content .= "Teacher ID: {$report['TeacherId']}\n";
        $content .= "Period: {$report['Period']}\n";
        $content .= "Status: {$report['Status']}\n";
        $content .= "Observation: {$report['Observation']}\n";
        $content .= "Skill Ratings: {$report['SkillRating']}\n";

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $filePath, $content);
        return ['status' => 'success', 'filepath' => $filePath];
    }

    function AddSkillRating($skill, $rating)
    {
        $sql = "SELECT skillRatings FROM progressreport WHERE reportID = ?";
        $report = Database::getInstance()->fetchOne($sql, [$this->ReportId]);
        $existing = $report ? json_decode($report['SkillRating'], true) : [];
        $existing[$skill] = $rating;
        $sql = "UPDATE progressreport SET skillRatings = ? WHERE reportID = ?";
        $params = [json_encode($existing), $this->ReportId];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function InsertReport($childId, $observations, $ratings, $status)
    {
        $this->ChildId = $childId;
        $this->TeacherId = $_SESSION['user_id'] ?? null;
        $this->Period = date('Y-m-d');
        $this->Observation = $observations;
        $this->SkillRating = json_encode(['rating' => $ratings]);
        $this->Status = $status;
        $this->CreatedAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO progressreport (childID, teacherID, period, observation, skillRatings, status, dueDate, createdAt)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$this->ChildId, $this->TeacherId, $this->Period, $this->Observation, $this->SkillRating, $this->Status, date('Y-m-d', strtotime('+7 days')), $this->CreatedAt];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }

    function GetPublishedReportsByChild($childId)
    {
        $sql = "SELECT * FROM progressreport WHERE childID = ? AND status = 'Published'";
        return Database::getInstance()->fetchAll($sql, [$childId]);
    }

    function GetOverdueReports($today)
    {
        $queries = [
            "SELECT * FROM ProgressReport WHERE dueDate < ? AND status IN ('Draft','Pending Review')",
            "SELECT * FROM ProgressReport WHERE due_date < ? AND status IN ('Draft','Pending Review')",
        ];

        foreach ($queries as $sql) {
            try {
                $rows = Database::getInstance()->fetchAll($sql, [$today]);
                if (!empty($rows)) {
                    return $rows;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return [];
    }

    function GetProgressReportsByChildId($ChildId)
    {
        $Database = Database::getInstance();
        $sql = "SELECT * FROM progressreport WHERE childID = ?";
        $params = [$ChildId];
        return $Database->fetchAll($sql, $params);
        // Code to get progress reports for child
    }

 }
?>