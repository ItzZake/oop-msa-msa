<?php
/**
 * Web-accessible controller test harness entry point
 * Access via: http://localhost/Yarab/tests/run_harness.php
 */

// Set response headers
header('Content-Type: text/plain; charset=utf-8');

// Run the controller harness
require __DIR__ . '/run_controllers.php';
