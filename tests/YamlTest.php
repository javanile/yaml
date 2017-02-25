<?php

use PHPUnit\Framework\TestCase;

final class YamlTest extends TestCase
{
    public function testLoadOneItemFile()
    {
        $yaml = \Javanile\Yaml\Yaml::resolve(__DIR__.'/samples/include-one-item.yml');

        $this->assertEquals($yaml, ['item' => 'one']);
    }
}
