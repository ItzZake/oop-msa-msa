<?php
require_once 'Database.php';

class Invoice
{
    public function AddCourseCharge($parentId, $enrollmentId)
    {
        $sql = "SELECT c.price FROM Enrollment e
                INNER JOIN Course c ON e.courseID = c.courseID
                WHERE e.enrollmentID = ? LIMIT 1";
        $result = Database::getInstance()->fetchOne($sql, [$enrollmentId]);
        if (empty($result['price'])) {
            return false;
        }

        $sql = "INSERT INTO Payment (subscriptionID, parentID, amount, gateway, status, createdAt)
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [null, $parentId, $result['price'], 'system', 'Pending', date('Y-m-d H:i:s')];
        $stmt = Database::getInstance()->query($sql, $params);
        return $stmt && $stmt->rowCount() > 0;
    }
}
