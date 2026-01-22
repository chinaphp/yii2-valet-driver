<?php


use Valet\Drivers\ValetDriver;

class LocalValetDriver extends ValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        if (file_exists($sitePath . '/yii')) {
            return true;
        }
        if (file_exists($sitePath . '/../vendor/yiisoft/yii2/Yii.php') || file_exists($sitePath . '/vendor/yiisoft/yii2/Yii.php')) {
            return true;
        }

        return false;
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        if (preg_match("#^assets#", $siteName)) {
            return $sitePath . $uri;
        }

        if (file_exists($staticFilePath = $sitePath . '/web/' . $uri) && !is_dir($staticFilePath) && pathinfo($staticFilePath)['extension'] != '.php') {
            return $staticFilePath;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): string
    {
        $uriPath = trim($uri, '/');
        $segments = $uriPath === '' ? [] : explode('/', $uriPath);
        $entry = $segments[0] ?? '';
        $entries = ['api', 'backend', 'oauth2'];

        if ($entry !== '' && in_array($entry, $entries, true) && file_exists($sitePath . '/web/' . $entry . '/index.php')) {
            $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
            $_SERVER['SCRIPT_FILENAME'] = $sitePath . '/web/' . $entry . '/index.php';
            $_SERVER['SCRIPT_NAME'] = '/' . $entry . '/index.php';
            $_SERVER['PHP_SELF'] = '/' . $entry . '/index.php';
            $_SERVER['DOCUMENT_ROOT'] = $sitePath;

            return $sitePath . '/web/' . $entry . '/index.php';
        }

        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_FILENAME'] = $sitePath . '/web/index.php';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['DOCUMENT_ROOT'] = $sitePath;

        return $sitePath . '/web/index.php';
    }
}
