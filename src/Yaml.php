<?php

namespace Javanile\Yaml;

use Symfony\Component\Yaml\Yaml as SymfonyYaml;

class Yaml extends SymfonyYaml
{
    /**
     * Parse YAML file and resolve included files
     *
     * @param $filename
     * @param string $includeTag
     */
    public static function resolve($filename, $includeTag='include')
    {
        //
        $yaml = self::parse(file_get_contents($filename));

        //
        $path = dirname(realpath($filename));

        //
        self::recursiveResolve($yaml, $path, $includeTag);

        //
        return $yaml;
    }

    private static function recursiveResolve(&$yaml, $path, $includeTag)
    {
        //
        if (!is_array($yaml)) {
            return;
        }

        //
        $includes = [];

        //
        foreach ($yaml as $key => &$node) {

            //
            if ($key == $includeTag) {

                //
                $filename = $path.'/'.$node;

                //
                if (file_exists($filename)) {

                    //
                    $include = self::resolve($filename, $includeTag);

                    //
                    $includes = array_merge($includes, $include);
                }

                //
                unset($yaml[$includeTag]);

                //
                continue;
            }

            //
            self::recursiveResolve($node, $path, $includeTag);
        }

        //
        if ($includes) {
            $yaml = array_merge($yaml, $includes);
        }
    }
}