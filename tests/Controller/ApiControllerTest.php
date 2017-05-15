<?php

namespace Tests\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use TweedeGolf\MediaBundle\Controller\ApiController;
use TweedeGolf\MediaBundle\Entity\FileRepository;
use TweedeGolf\MediaBundle\Entity\FileSerializer;
use Tests\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\ParameterBag;
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
        $paginator = $this->getMockBuilder(Paginator::class)->disableOriginalConstructor()->getMock();
        $query = $this->getMockBuilder(ParameterBag::class)->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->query = $query;
        $repository = $this->getMockBuilder(FileRepository::class)->disableOriginalConstructor()->getMock();
        $serializer = $this->getMockBuilder(FileSerializer::class)->disableOriginalConstructor()->getMock();

        $this->container->expects($this->once())->method('getParameter')->with('tweede_golf_media.max_per_page')
            ->will($this->returnValue(10));
        $this->container->expects($this->at(1))->method('get')->with('tweedegolf.repository.file')
            ->will($this->returnValue($repository));
        $repository->expects($this->once())->method('findSubset')->will($this->returnValue($paginator));
        $this->container->expects($this->at(2))->method('get')->with('tweedegolf.media.file_serializer')
            ->will($this->returnValue($serializer));

        $this->controller->indexAction($request);
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
