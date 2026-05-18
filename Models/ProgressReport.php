<?php
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

    function SubmitReport()
    {
        // Code to submit progress report for child
    }

    function PublishReport($AdminId)
    {
        // Code to publish progress report for child
    }

    function ExportPDF()
    {
        // Code to export progress report as PDF
    }

    function AddSkillRating($skill, $rating)
    {
        // Code to add skill rating to progress report
    }
    function GetProgressReportsByChildId($ChildId)
    {
        $Database = Database::getInstance();
        $sql = "SELECT * FROM ProgressReports WHERE ChildId = ?";
        $params = [$ChildId];
        return $Database->fetchAll($sql, $params);
        // Code to get progress reports for child
    }

 }
?>