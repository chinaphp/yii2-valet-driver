<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\ValetDriver;

class Yii2ValetDriver extends ValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {

        if (file_exists($sitePath . '/yii')) {
            return true;
        }
        if (file_exists($sitePath . '/../vendor/yiisoft/yii2/Yii.php') || file_exists($sitePath . '/vendor/yiisoft/yii2/Yii.php')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        // this works for domains called code assets
        // for example your site name product.{valet-domen} assets domen is assets.product.{valet-domen}
        if (preg_match("#^assets#", $siteName)) {
            return $sitePath . $uri;
        }

        if (file_exists($staticFilePath = $sitePath . '/web/' . $uri) && !is_dir($staticFilePath) && pathinfo($staticFilePath)['extension'] != '.php') {
            return $staticFilePath;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {

        $uri_path = explode('/', $uri)[1];


        if (file_exists($sitePath . '/web/' . $uri_path . '/index.php') && !empty($uri_path)) {

            $_SERVER['SCRIPT_FILENAME'] = $sitePath . '/web/' . $uri_path . '/index.php';
            $_SERVER['SCRIPT_NAME'] = '/' . $uri_path . '/index.php';
            $_SERVER['PHP_SELF'] = '/' . $uri_path . '/index.php';
            $_SERVER['DOCUMENT_ROOT'] = $sitePath;

            return $sitePath . '/web/' . $uri_path . '/index.php';
        }

        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_FILENAME'] = $sitePath . '/web/index.php';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['DOCUMENT_ROOT'] = $sitePath;

        return $sitePath . '/web/index.php';
    }
}
