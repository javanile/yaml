<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers Email
 */
final class YamlTest extends TestCase
{
    public function testLoadOneItemFile()
    {
        $yaml = \Javanile\Yaml\Yaml::resolve(__DIR__.'/samples/one-item.yml');

        $this->assertEquals($yaml, ['item' => 'one']);
    }
}
