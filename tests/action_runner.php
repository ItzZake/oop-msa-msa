<?php
// Run a single controller scenario in a fresh PHP process.
$scenarioFile = __DIR__ . '/action_scenarios.php';

if ($argc < 2) {
    fwrite(STDERR, "Usage: php action_runner.php <controllerPath> [scenarioIndex]\n");
    exit(2);
}

$controllerPath = $argv[1];
$scenarioIndex = isset($argv[2]) ? $argv[2] : null;

if (!file_exists($controllerPath)) {
    fwrite(STDERR, "Controller not found: $controllerPath\n");
    exit(2);
}

$scenariosByController = array();
if (file_exists($scenarioFile)) {
    $scenariosByController = require $scenarioFile;
}

$controllerName = basename($controllerPath);
$scenario = null;

if ($scenarioIndex !== null && isset($scenariosByController[$controllerName][$scenarioIndex])) {
    $scenario = $scenariosByController[$controllerName][$scenarioIndex];
} elseif (isset($scenariosByController[$controllerName][0])) {
    $scenario = $scenariosByController[$controllerName][0];
}

if ($scenario === null) {
    $scenario = array(
        'name' => 'default',
        'method' => 'GET',
        'session' => array('user_role' => 'admin', 'user_id' => '1'),
        'server' => array('HTTP_HOST' => 'localhost', 'REMOTE_ADDR' => '127.0.0.1'),
    );
}

require __DIR__ . '/mocks.php';

chdir(dirname($controllerPath));
$includePath = basename($controllerPath);

if (!file_exists($includePath)) {
    fwrite(STDERR, "Controller include path not found: $includePath\n");
    sendJsonOutput(array(
        'status' => 'error',
        'message' => "Controller include path not found: $includePath",
        'output' => base64_encode(''),
    ));
    exit(1);
}

$_GET = isset($scenario['get']) ? $scenario['get'] : array();
$_POST = isset($scenario['post']) ? $scenario['post'] : array();
$_REQUEST = array_merge($_GET, $_POST);
$_FILES = isset($scenario['files']) ? $scenario['files'] : array();
$_SESSION = isset($scenario['session']) ? $scenario['session'] : array('user_role' => 'admin', 'user_id' => '1');
$_SERVER = isset($scenario['server']) ? $scenario['server'] : array();
$_SERVER['REQUEST_METHOD'] = isset($scenario['method']) ? $scenario['method'] : 'GET';

$hasOutput = false;

function sendJsonOutput($payload) {
    global $hasOutput;
    if ($hasOutput) {
        return;
    }
    $hasOutput = true;
    echo json_encode($payload);
}

register_shutdown_function(function () use (&$hasOutput) {
    if ($hasOutput) {
        return;
    }

    $lastError = error_get_last();
    $output = ob_get_contents();
    ob_end_clean();

    if ($lastError && in_array($lastError['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR), true)) {
        sendJsonOutput(array(
            'status' => 'error',
            'message' => $lastError['message'],
            'output' => base64_encode($output),
        ));
        return;
    }

    sendJsonOutput(array(
        'status' => 'ok',
        'output' => base64_encode($output),
    ));
});

ob_start();
try {
    include $includePath;
    if (!$hasOutput) {
        $output = ob_get_clean();
        sendJsonOutput(array(
            'status' => 'ok',
            'output' => base64_encode($output),
        ));
    }
    exit(0);
} catch (Exception $e) {
    ob_end_clean();
    sendJsonOutput(array(
        'status' => 'error',
        'message' => $e->getMessage(),
    ));
    exit(1);
}
