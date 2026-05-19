<?php
require_once 'Database.php';

class Rsvp
{
    public function upsertRsvp($childId, $eventId, $response, $timestamp)
    {
        $sql = "SELECT * FROM EventRSVP WHERE childID = ? AND eventID = ?";
        $existing = Database::getInstance()->fetchOne($sql, [$childId, $eventId]);

        if ($existing) {
            $sql = "UPDATE EventRSVP SET response = ?, respondedAt = ? WHERE childID = ? AND eventID = ?";
            $params = [$response, $timestamp, $childId, $eventId];
        } else {
            $sql = "INSERT INTO EventRSVP (childID, eventID, response, respondedAt) VALUES (?, ?, ?, ?)";
            $params = [$childId, $eventId, $response, $timestamp];
        }

        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt !== false;
    }

    public function getAttendeesWithConsentByEvent($eventId)
    {
        $sql = "SELECT c.childID AS child_id,
                       c.name AS child_name,
                       r.response AS rsvp_response,
                       CASE WHEN cf.isSigned = 1 THEN 'Signed' ELSE 'Unsigned' END AS consent_status,
                       CONCAT(p.firstName, ' ', p.lastName) AS parent_name
                FROM EventRSVP r
                INNER JOIN Child c ON r.childID = c.childID
                INNER JOIN Parent p ON r.parentID = p.parentID
                LEFT JOIN ConsentForm cf ON cf.eventID = r.eventID AND cf.childID = r.childID
                WHERE r.eventID = ?";
        return Database::getInstance()->fetchAll($sql, [$eventId]);
    }

    public function getConfirmedRsvpsByEvent($eventId)
    {
        $sql = "SELECT childID AS child_id, parentID AS parent_id FROM EventRSVP WHERE eventID = ? AND LOWER(response) = 'yes'";
        return Database::getInstance()->fetchAll($sql, [$eventId]);
    }
}
?>