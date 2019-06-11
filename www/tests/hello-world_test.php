<?php
use PHPUnit\Framework\TestCase;

require "hello-world.php";

class HelloWorldTest extends TestCase
{
    public function testNoName()
    {
        $this->assertEquals('Hello, World!', helloWorld());
    }

    public function testSampleName()
    {
        $this->assertEquals('Hello, Alice!', helloWorld('Alice'));
    }

    public function testAnotherSampleName()
    {

        $this->assertEquals('Hello, Bob!', helloWorld('bob'));
    }

    public function test2AnotherSampleName()
    {
        $this->assertEquals('Hello, Pierre!', helloWorld('PIERRE'));
    }
}
