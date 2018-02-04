<?php

namespace Bitty\Tests\Http;

use Bitty\Http\ResponseServiceProvider;
use Bitty\Tests\Http\TestCase;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseServiceProviderTest extends TestCase
{
    /**
     * @var ResponseServiceProvider
     */
    protected $fixture = null;

    protected function setUp()
    {
        parent::setUp();

        $this->fixture = new ResponseServiceProvider();
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(ServiceProviderInterface::class, $this->fixture);
    }

    public function testGetFactories()
    {
        $actual = $this->fixture->getFactories();

        $this->assertEquals([], $actual);
    }

    public function testGetExtensions()
    {
        $actual = $this->fixture->getExtensions();

        $this->assertEquals(['response'], array_keys($actual));
        $this->assertInternalType('callable', $actual['response']);
    }

    public function testCallbackResponseWithoutPrevious()
    {
        $extensions = $this->fixture->getExtensions();
        $callable   = reset($extensions);

        $container = $this->createMock(ContainerInterface::class);
        $actual    = $callable($container);

        $this->assertInstanceOf(ResponseInterface::class, $actual);
    }

    public function testCallbackResponseWithPrevious()
    {
        $extensions = $this->fixture->getExtensions();
        $callable   = reset($extensions);

        $container = $this->createMock(ContainerInterface::class);
        $previous  = $this->createMock(ResponseInterface::class);
        $actual    = $callable($container, $previous);

        $this->assertSame($previous, $actual);
    }
}