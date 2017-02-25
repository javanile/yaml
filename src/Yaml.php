<?php
/**
 * Command line tool for vendor code.
 *
 * PHP version 5
 *
 * @category   CommandLine
 *
 * @author     Francesco Bianco <bianco@javanile.org>
 * @copyright  2015-2017 Javanile.org
 * @license    https://github.com/Javanile/Producer/blob/master/LICENSE  MIT License
 */

namespace Javanile\Yaml;

use Symfony\Component\Yaml\Yaml as SymfonyYaml;

/**
 * Class Yaml.
 */
class Yaml extends SymfonyYaml
{
    /**
     * Parse YAML file and resolve included files
     *
     * @param $filename
     * @param string $includeTag
     * @return array
     */
    public static function resolve($filename, $includeTag = 'include')
    {
        $path = dirname(realpath($filename));
        $yaml = self::parse(file_get_contents($filename));

        self::recursiveResolve($yaml, $path, $includeTag);

        return $yaml;
    }

    /**
     * Walk through array and find include tag.
     *
     * @param array $yaml        reference of an array
     * @param string $path       base path for relative inclusion
     * @param string $includeTag tag to include file
     */
    private static function recursiveResolve(&$yaml, $path, $includeTag)
    {
        if (!is_array($yaml)) {
            return;
        }

        $includes = [];

        foreach ($yaml as $key => &$node) {
            if ($key == $includeTag) {
                $filename = $path.'/'.$node;

                if (file_exists($filename)) {
                    $include = self::resolve($filename, $includeTag);
                    $includes = array_merge($includes, $include);
                }

                unset($yaml[$includeTag]);

                continue;
            }

            self::recursiveResolve($node, $path, $includeTag);
        }

        if ($includes) {
            $yaml = array_merge($yaml, $includes);
        }
    }
}
