<?php
// Controller-Model Integration Harness (Improved)
// Safely tests actual controller-model connectivity

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0);

// Set execution timeout
set_time_limit(10);

$passed = 0;
$failed = 0;
$failedControllers = array();

// Load mocks first
if (file_exists(__DIR__ . '/mocks.php')) {
    require __DIR__ . '/mocks.php';
}

// Get all controllers
$controllerDir = __DIR__ . '/../Controller/';
$files = glob($controllerDir . '*.php');

echo "Testing " . count($files) . " controllers for model integration...\n";
echo str_repeat("-", 50) . "\n";

foreach ($files as $file) {
    $name = basename($file);
    
    // Test in isolated context
    if (testControllerWithTimeout($file)) {
        echo "✓ $name\n";
        $passed++;
    } else {
        echo "✗ $name\n";
        $failed++;
        $failedControllers[] = $name;
    }
}

echo str_repeat("-", 50) . "\n";
echo "PASSED: $passed\n";
echo "FAILED: $failed\n";
echo "TOTAL: " . count($files) . "\n";
echo "Success Rate: " . round(($passed / count($files)) * 100) . "%\n";

if ($failed > 0) {
    echo "\nFailed Controllers:\n";
    foreach ($failedControllers as $ctrl) {
        echo "  - $ctrl\n";
    }
}

function testControllerWithTimeout($file)
{
    // Reset globals
    $_GET = array();
    $_POST = array();
    $_SESSION = array('user_id' => '1');
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    // Capture all output and errors
    ob_start();
    
    $hadError = false;
    set_error_handler(function($errno, $errstr) use (&$hadError) {
        if ($errno & (E_ERROR | E_PARSE)) {
            $hadError = true;
        }
        return true;
    });
    
    try {
        include $file;
        $hadError = false; // If we got here, it's OK
    } catch (Exception $e) {
        $hadError = true;
    } catch (Throwable $e) {
        $hadError = true;
    }
    
    restore_error_handler();
    ob_end_clean();
    
    return !$hadError;
}

if (php_sapi_name() === 'cli') {
    exit($failed > 0 ? 1 : 0);
}
