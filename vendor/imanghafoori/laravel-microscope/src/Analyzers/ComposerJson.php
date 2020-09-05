<?php

namespace Imanghafoori\LaravelMicroscope\Analyzers;

use Illuminate\Support\Str;

class ComposerJson
{
    private static $result = [];

    public static function readKey($key, $composerPath = null)
    {
        $path = $composerPath ?: '';

        if (isset(self::$result[$path][$key])) {
            return self::$result[$path][$key];
        }

        $composer = json_decode(file_get_contents(  app()->basePath($path. 'composer.json')  ), true);

        $value = (array) data_get($composer, $key, []);

        if (in_array($key, ['autoload.psr-4', 'autoload-dev.psr-4'])) {
            $value = self::normalizePaths($value, $path);
        }

        return self::$result[$path][$key] = $value;
    }

    public static function readAutoload()
    {
        $composers = [''];

        foreach (self::readKey('repositories') as $repo) {
            if ($repo['type'] == 'path') {
                $composers[] = (trim($repo['url'], "./")).DIRECTORY_SEPARATOR.'';
            }
        }

        $res = [];
        foreach ($composers as $path) {
            $res = $res + self::readKey('autoload.psr-4', $path) + self::readKey('autoload-dev.psr-4', $path);
        }

        return $res;
    }

    private static function normalizePaths($value, $path)
    {
        foreach ($value as $namespace => $_path) {
            if (! Str::endsWith($_path, ['/'])) {
                $value[$namespace] .= '/';
            }

            $value[$namespace] = $path.$value[$namespace];
        }

        return $value;
    }
}