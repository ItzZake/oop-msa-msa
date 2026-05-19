<?php
/**
 * DashboardController.php
 *
 * Handles dashboard data retrieval and overview statistics.
 * Counts students from the Child table directly.
 * Counts teachers from the Teacher table directly.
 */

class DashboardController {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../Models/Database.php';
        $this->db = Database::getInstance();
    }

    public function addUser(array $data) {
        try {
            return $this->db->addUser($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function editUser(int $userId, array $data) {
        try {
            return $this->db->updateUser($userId, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteUser(int $userId) {
        try {
            return $this->db->deleteUser($userId);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Try multiple table name variants and return COUNT(*).
     * Returns -1 if NO table in the list exists (so caller can distinguish
     * "table not found" from "table exists but is empty").
     */
    private function tryCount(array $tables, string $extraWhere = ''): int {
        foreach ($tables as $table) {
            try {
                $sql = "SELECT COUNT(*) AS cnt FROM `{$table}`";
                if ($extraWhere) {
                    $sql .= ' WHERE ' . $extraWhere;
                }
                $result = $this->db->fetchOne($sql);
                if ($result !== null && array_key_exists('cnt', $result)) {
                    return (int)$result['cnt'];
                }
            } catch (Exception $e) {
                // This table variant doesn't exist — try the next one
                continue;
            }
        }
        return 0;
    }

    public function loadOverview() {
        $data = [
            'students'        => [],
            'active_alerts'   => 0,
            'active_classes'  => 0,
            'enrollment_rate' => 0,
            'total_students'  => 0,
            'total_teachers'  => 0,
            'attendance_rate' => 0,
        ];

        try {

            // ── 1. RECENT USERS for the "Recent Users" table ──────────────────
            $users = null;
            foreach (['User', 'Users', 'user', 'users'] as $tbl) {
                try {
                    $q = "SELECT userID, firstname, Lastname, Role, email, createdAt
                          FROM `{$tbl}`
                          ORDER BY userID DESC
                          LIMIT 5";
                    $rows = $this->db->fetchAll($q);
                    if ($rows !== null) {          // even an empty result is fine
                        $users = $rows;
                        break;
                    }
                } catch (Exception $e) {
                    continue;
                }
            }

            if (!empty($users)) {
                foreach ($users as $row) {
                    $name = trim(($row['firstname'] ?? '') . ' ' . ($row['Lastname'] ?? ''));
                    $data['students'][] = [
                        'userID'    => $row['userID']    ?? '',
                        'name'      => $name ?: ($row['email'] ?? 'Unknown'),
                        'role'      => $row['Role']      ?? 'User',
                        'email'     => $row['email']     ?? '',
                        'createdAt' => $row['createdAt'] ?? '',
                    ];
                }
            }

            // ── 2. TOTAL STUDENTS — count rows in the Child / Children table ──
            $data['total_students'] = $this->tryCount(
                ['Child', 'child', 'Children', 'children']
            );

            // ── 3. TOTAL TEACHERS — count rows in the Teacher table ───────────
            $data['total_teachers'] = $this->tryCount(
                ['Teacher', 'teacher', 'Teachers', 'teachers']
            );

            // ── 4. ACTIVE ALERTS ──────────────────────────────────────────────
            $data['active_alerts'] = $this->tryCount(
                ['alerts', 'alert', 'Alerts', 'Alert']
            );

            // ── 5. ACTIVE CLASSES ─────────────────────────────────────────────
            $data['active_classes'] = $this->tryCount(
                ['Classes', 'classes', 'Class', 'class']
            );

            // ── 6. ENROLLMENT RATE from Child table ───────────────────────────
            $enrollmentRate = 0;
            foreach (['Child', 'child', 'Children', 'children'] as $ct) {
                try {
                    $q   = "SELECT ROUND(
                                (COUNT(CASE WHEN enrollmentStatus = 'Approved' THEN 1 END)
                                 / NULLIF(COUNT(*), 0)) * 100
                            ) AS rate
                            FROM `{$ct}`";
                    $res = $this->db->fetchOne($q);
                    if ($res && isset($res['rate'])) {
                        $enrollmentRate = (int)$res['rate'];
                        break;
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
            $data['enrollment_rate'] = $enrollmentRate;

            // ── 7. TODAY'S ATTENDANCE RATE ────────────────────────────────────
            $attendanceRate = 0;
            foreach (['Attendance', 'attendance'] as $at) {
                try {
                    $q   = "SELECT ROUND(
                                (COUNT(CASE WHEN status = 'Present' THEN 1 END)
                                 / NULLIF(COUNT(*), 0)) * 100
                            ) AS rate
                            FROM `{$at}`
                            WHERE DATE(date) = CURDATE()";
                    $res = $this->db->fetchOne($q);
                    if ($res && isset($res['rate'])) {
                        $attendanceRate = (int)$res['rate'];
                        break;
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
            $data['attendance_rate'] = $attendanceRate;

        } catch (Exception $e) {
            error_log("Dashboard overview error: " . $e->getMessage());
        }

        return $data;
    }
}