<?php

namespace Tests\Controller;

use TweedeGolf\MediaBundle\Controller\ApiController;
use Tests\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;


class UserControllerTest extends TestCase
{
    // TODO.....
    protected $controller;
    protected $container;
    protected $router;
    protected $templating;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
        $this->router = $this->createMock(RouterInterface::class);
        $this->templating = $this->createMock(EngineInterface::class);
        $this->controller = new ApiController();
        $this->controller->setContainer($this->container);
    }

    public function testModalAction()
    {
        $this->container->expects($this->once())->method('has')->with('templating')
            ->will($this->returnValue(true));
        $this->container->expects($this->once())->method('get')->with('templating')
            ->will($this->returnValue($this->templating));

        $response = $this->createMock(Response::class);
        $this->templating->expects($this->once())->method('renderResponse')->will($this->returnValue($response));

        $this->controller->modalAction();
    }

    public function testIndexAction()
    {
        $this->markTestIncomplete();
    }

    public function testCreateAction()
    {
        $this->markTestIncomplete();
    }

    public function testDeleteAction()
    {
        $this->markTestIncomplete();
    }
}
