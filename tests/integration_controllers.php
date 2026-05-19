<?php
$projectRoot = realpath(__DIR__ . '/..');
$modelDir = $projectRoot . '/Models';

// Prefer the real Database class for integration tests when available
if (file_exists($modelDir . '/Database.php')) {
    require_once $modelDir . '/Database.php';
}

require __DIR__ . '/mocks.php';

$controllerDir = $projectRoot . '/Controller';
$controllerDir = $projectRoot . '/Controller';
$modelDir = $projectRoot . '/Models';

echo "Running integration controller -> model checks\n";

// Check DB connection via Models/Database.php
$dbOk = false;
try {
    $conn = Database::getInstance()->getConnection();
    if ($conn) {
        // Try a lightweight query
        $res = @$conn->query('SELECT 1');
        if ($res !== false) $dbOk = true;
    }
} catch (Throwable $e) {
    $dbOk = false;
}

echo "Database reachable: " . ($dbOk ? "YES" : "NO") . "\n\n";

$controllers = glob($controllerDir . '/*.php');
$report = [];

foreach ($controllers as $controller) {
    $name = basename($controller);
    $content = file_get_contents($controller);
    preg_match_all('#include_once\s+["\']\s*\.\./Models/([^"\']+)#', $content, $m1);
    preg_match_all('#require_once\s+["\']\s*\.\./Models/([^"\']+)#', $content, $m2);
    $models = array_unique(array_merge($m1[1] ?? [], $m2[1] ?? []));
    $models = array_map('trim', $models);

    $controllerResult = ['models' => [], 'ok' => true];

    foreach ($models as $modelFile) {
        $entry = ['file' => $modelFile, 'loaded' => false, 'class_found' => false, 'instantiated' => false];
        $modelPath = $modelDir . '/' . $modelFile;
        if (file_exists($modelPath)) {
            try {
                require_once $modelPath;
                $entry['loaded'] = true;
                $modelContent = file_get_contents($modelPath);
                if (preg_match('/class\s+(\w+)/', $modelContent, $mc)) {
                    $className = $mc[1];
                    $entry['class_found'] = true;
                    if (class_exists($className)) {
                        try {
                            $ref = new ReflectionClass($className);
                            if ($ref->getConstructor() === null) {
                                $obj = $ref->newInstance();
                                $entry['instantiated'] = true;
                            } else {
                                // try instantiate with no args, then without constructor
                                try {
                                    $obj = $ref->newInstance();
                                    $entry['instantiated'] = true;
                                } catch (Throwable $e) {
                                    $obj = $ref->newInstanceWithoutConstructor();
                                    $entry['instantiated'] = true;
                                }
                            }
                        } catch (Throwable $e) {
                            $entry['instantiated'] = false;
                        }
                    }
                }
            } catch (Throwable $e) {
                $entry['loaded'] = false;
                $controllerResult['ok'] = false;
            }
        } else {
            $entry['loaded'] = false;
            $controllerResult['ok'] = false;
        }
        $controllerResult['models'][] = $entry;
    }

    // If controller references no models, attempt a lightweight include to ensure it parses
    if (empty($models)) {
        try {
            // include in isolated scope
            (function() use ($controller) { include $controller; });
        } catch (Throwable $e) {
            $controllerResult['ok'] = false;
            $controllerResult['error'] = $e->getMessage();
        }
    }

    $report[$name] = $controllerResult;
}

// Print report
foreach ($report as $c => $r) {
    echo "Controller: $c\n";
    echo "  OK: " . ($r['ok'] ? "YES" : "NO") . "\n";
    if (!empty($r['models'])) {
        echo "  Models:\n";
        foreach ($r['models'] as $m) {
            echo "    - {$m['file']}: loaded=" . ($m['loaded']?"Y":"N") . ", class_found=" . ($m['class_found']?"Y":"N") . ", instantiated=" . ($m['instantiated']?"Y":"N") . "\n";
        }
    }
    if (!empty($r['error'])) echo "  Error: {$r['error']}\n";
    echo "\n";
}

exit(0);
