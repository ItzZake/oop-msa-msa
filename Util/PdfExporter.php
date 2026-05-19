<?php
require_once __DIR__ . '/../Models/Database.php';

class PdfExporter
{
    public function exportAttendeeList(array $attendeeData, $eventId)
    {
        $uploadDir = '/uploads/reports/';
        $fileName = 'event_attendees_' . $eventId . '_' . date('YmdHis') . '.pdf';
        $filePath = $uploadDir . $fileName;

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $uploadDir)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $uploadDir, 0755, true);
        }

        $content = "Event Attendee List for Event ID: {$eventId}\n\n";
        foreach ($attendeeData as $row) {
            $content .= "Child ID: " . ($row['child_id'] ?? '') . "\n";
            $content .= "Child Name: " . ($row['child_name'] ?? '') . "\n";
            $content .= "RSVP Response: " . ($row['rsvp_response'] ?? '') . "\n";
            $content .= "Consent Status: " . ($row['consent_status'] ?? '') . "\n";
            $content .= "Parent Name: " . ($row['parent_name'] ?? '') . "\n";
            $content .= str_repeat('-', 40) . "\n";
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $filePath, $content);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        readfile($_SERVER['DOCUMENT_ROOT'] . $filePath);
        return ['status' => 'success', 'filepath' => $filePath];
    }

    public function exportProgressReport($reportId, $childId)
    {
        $report = Database::getInstance()->fetchOne("SELECT * FROM progressreport WHERE reportID = ? AND childID = ?", [$reportId, $childId]);
        if (!$report) {
            return ['status' => 'error', 'message' => 'Report not found'];
        }

        $uploadDir = '/uploads/reports/';
        $fileName = 'progress_report_' . $reportId . '_' . date('YmdHis') . '.pdf';
        $filePath = $uploadDir . $fileName;

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $uploadDir)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $uploadDir, 0755, true);
        }

        $content = "Progress Report ID: {$reportId}\n";
        $content .= "Child ID: {$childId}\n";
        $content .= "Teacher ID: " . ($report['teacherID'] ?? '') . "\n";
        $content .= "Period: " . ($report['period'] ?? '') . "\n";
        $content .= "Status: " . ($report['status'] ?? '') . "\n";
        $content .= "Observation: " . ($report['observation'] ?? '') . "\n";
        $content .= "Skill Rating: " . ($report['skillRating'] ?? '') . "\n";

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $filePath, $content);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        readfile($_SERVER['DOCUMENT_ROOT'] . $filePath);
        return ['status' => 'success', 'filepath' => $filePath];
    }
}
