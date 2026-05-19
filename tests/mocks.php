<?php
// Lightweight mocks for models to allow controllers to be included safely in tests.

// Only define mocks for classes where the real model file does not exist.
$modelsDir = __DIR__ . '/../Models';

if (!class_exists('Database') && !file_exists($modelsDir . '/Database.php')) {
    class Database {
        public static function getInstance() {
            static $inst = null;
            if ($inst === null) {
                $inst = new self();
            }
            return $inst;
        }
        public function query($sql, $params = []) { return false; }
        public function fetchAll($stmt) { return []; }
        public function fetchOne($stmt) { return null; }
        public function getConnection() { return null; }
    }
}

if (!class_exists('Event') && !file_exists($modelsDir . '/Event.php')) {
    class Event {
        public function __construct(...$args) {}
        public function InsertGalleryPhoto($eventID, $photoPath, $caption = '') { return true; }
    }
}

if (!class_exists('Notification') && !file_exists($modelsDir . '/Notification.php')) {
    class Notification {
        public function __construct(...$args) {}
        public function Send() { return true; }
    }
}

if (!class_exists('Child') && !file_exists($modelsDir . '/Child.php')) {
    class Child {
        public function __construct(...$args) {}
    }
}

if (!class_exists('Course') && !file_exists($modelsDir . '/Course.php')) {
    class Course {
        public function __construct(...$args) {}
    }
}

if (!class_exists('Flag') && !file_exists($modelsDir . '/Flag.php')) {
    class Flag {
        public function __construct(...$args) {}
    }
}

if (!class_exists('User') && !file_exists($modelsDir . '/User.php')) {
    class User {
        public function __construct(...$args) {}
    }
}

// Ensure session functions work in CLI tests
if (session_status() === PHP_SESSION_NONE) {
    if (!function_exists('session_start')) {
        function session_start() { return true; }
    }
}

?>
