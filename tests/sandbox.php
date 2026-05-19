<?php
namespace TestSandbox;

class SandboxExitException extends \Exception
{
    public $exitCode;

    public function __construct($exitCode = 0)
    {
        parent::__construct('Sandbox exit');
        $this->exitCode = (int) $exitCode;
    }
}

function runControllerWithSandbox($controllerPath, &$capturedHeaders = null)
{
    global $SANDBOX_HEADERS;
    $SANDBOX_HEADERS = array();
    $capturedHeaders = &$SANDBOX_HEADERS;

    // Temporarily override header() and exit() by replacing them in code
    $controllerCode = file_get_contents($controllerPath);
    
    // Inject sandbox namespace at top of controller
    $controllerCode = '<?php namespace TestSandbox; ' . substr($controllerCode, 5);
    
    // Replace exit; statements with sandboxExit()
    $controllerCode = preg_replace('/\bexit\s*;/', 'sandboxExit();', $controllerCode);
    // Replace exit(code) with sandboxExit(code)
    $controllerCode = preg_replace('/\bexit\s*\(/', 'sandboxExit(', $controllerCode);
    // Replace header() calls with sandboxHeader()
    $controllerCode = preg_replace('/\bheader\s*\(/', 'sandboxHeader(', $controllerCode);

    eval($controllerCode);
}

function sandboxHeader($string, $replace = true, $http_response_code = null)
{
    global $SANDBOX_HEADERS;
    if (!isset($SANDBOX_HEADERS)) {
        $SANDBOX_HEADERS = array();
    }
    $SANDBOX_HEADERS[] = $string;
}

function sandboxExit($status = 0)
{
    throw new SandboxExitException($status);
}

function sandboxSession_start()
{
    return true;
}

function sandboxSetcookie($name, $value = '', $expire = 0, $path = '', $domain = '', $secure = false, $httponly = false)
{
    return true;
}

function sandboxHeader_remove($name = null)
{
    return true;
}
