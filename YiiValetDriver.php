<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\ValetDriver;

/**
 * Yii Framework Valet Driver
 *
 * Supports both Yii2 and Yii3 applications:
 * - Yii2: Uses /web/ directory, checks for /yii file or yiisoft/yii2 package
 * - Yii3: Uses /public/ directory, checks for bin/yii file or yiisoft/yii package
 *
 * @author Valet Driver for Yii Framework
 */
class YiiValetDriver extends ValetDriver
{
    /**
     * Supported web directories in priority order
     */
    private const WEB_DIRECTORIES = [
        'public',  // Yii3 / modern structure
        'web',     // Yii2
    ];

    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        // Check for Yii3 indicators
        if ($this->isYii3Project($sitePath)) {
            return true;
        }

        // Check for Yii2 indicators
        if ($this->isYii2Project($sitePath)) {
            return true;
        }

        // Check if any web directory exists with index.php
        foreach (self::WEB_DIRECTORIES as $webDir) {
            if (file_exists($sitePath . '/' . $webDir . '/index.php')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if project is Yii3
     *
     * @param  string  $sitePath
     * @return bool
     */
    private function isYii3Project(string $sitePath): bool
    {
        // Check for Yii3 specific files
        if (file_exists($sitePath . '/bin/yii')) {
            return true;
        }

        // Check for yiisoft/yii package
        if (file_exists($sitePath . '/vendor/yiisoft/yii') ||
            file_exists($sitePath . '/../vendor/yiisoft/yii')) {
            return true;
        }

        // Check for Yii3 config files
        if (file_exists($sitePath . '/config/web.php') &&
            file_exists($sitePath . '/public/index.php')) {
            return true;
        }

        return false;
    }

    /**
     * Check if project is Yii2
     *
     * @param  string  $siteName
     * @param  string  $sitePath
     * @return bool
     */
    private function isYii2Project(string $sitePath): bool
    {
        // Check for Yii2 console file
        if (file_exists($sitePath . '/yii')) {
            return true;
        }

        // Check for yiisoft/yii2 package
        if (file_exists($sitePath . '/vendor/yiisoft/yii2/Yii.php') ||
            file_exists($sitePath . '/../vendor/yiisoft/yii2/Yii.php')) {
            return true;
        }

        return false;
    }

    /**
     * Get the web directory for the project
     *
     * @param  string  $sitePath
     * @return string|null
     */
    private function getWebDirectory(string $sitePath): ?string
    {
        foreach (self::WEB_DIRECTORIES as $webDir) {
            if (file_exists($sitePath . '/' . $webDir . '/index.php')) {
                return $webDir;
            }
        }

        return null;
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        // Support asset subdomain
        // Example: site name is "product.test", assets domain is "assets.product.test"
        if (preg_match('#^assets#', $siteName)) {
            return $sitePath . $uri;
        }

        $webDir = $this->getWebDirectory($sitePath);

        if ($webDir === null) {
            return false;
        }

        if (file_exists($staticFilePath = $sitePath . '/' . $webDir . '/' . $uri) &&
            !is_dir($staticFilePath) &&
            pathinfo($staticFilePath)['extension'] != '.php') {
            return $staticFilePath;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * Supports multiple entry points like:
     * - /api/index.php
     * - /backend/index.php
     * - /admin/index.php
     * - Default: /index.php
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath(string $sitePath, string $siteName, string $uri): string
    {
        $uriPath = trim($uri, '/');
        $segments = $uriPath === '' ? [] : explode('/', $uriPath);
        $entry = $segments[0] ?? '';

        // Configurable entry points
        $entries = ['api', 'backend', 'admin', 'oauth2', 'v1', 'v2'];

        $webDir = $this->getWebDirectory($sitePath);

        if ($webDir === null) {
            // Fallback to default web directory
            $webDir = 'web';
        }

        // Check for entry point subdirectory
        if ($entry !== '' && in_array($entry, $entries, true) &&
            file_exists($entryPath = $sitePath . '/' . $webDir . '/' . $entry . '/index.php')) {
            $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
            $_SERVER['SCRIPT_FILENAME'] = $entryPath;
            $_SERVER['SCRIPT_NAME'] = '/' . $entry . '/index.php';
            $_SERVER['PHP_SELF'] = '/' . $entry . '/index.php';
            $_SERVER['DOCUMENT_ROOT'] = $sitePath;

            return $entryPath;
        }

        // Default entry point
        $defaultPath = $sitePath . '/' . $webDir . '/index.php';

        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_FILENAME'] = $defaultPath;
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['DOCUMENT_ROOT'] = $sitePath;

        return $defaultPath;
    }
}
