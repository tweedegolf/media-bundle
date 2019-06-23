<?php

namespace Tests\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Tests\TestCase;
use TweedeGolf\MediaBundle\Controller\ApiController;
use TweedeGolf\MediaBundle\Entity\FileRepository;
use TweedeGolf\MediaBundle\Entity\FileSerializer;

class ApiControllerTest extends TestCase
{
    // TODO.....
    protected $controller;
    protected $container;
    protected $router;
    protected $templating;

    protected function setUp(): void
    {
        $this->container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
        $this->router = $this->createMock(RouterInterface::class);
        $this->templating = $this->createMock(EngineInterface::class);
        $this->controller = new ApiController();
        $this->controller->setContainer($this->container);
    }

    public function testModalAction(): void
    {
        $this->container->expects($this->once())->method('has')->with('templating')
            ->willReturn(true);
        $this->container->expects($this->once())->method('get')->with('templating')
            ->willReturn($this->templating);

        $response = $this->createMock(Response::class);
        $this->templating->expects($this->once())->method('render')->willReturn($response);

        $this->controller->modalAction();
    }

    public function testIndexAction(): void
    {
        $paginator = $this->getMockBuilder(Paginator::class)->disableOriginalConstructor()->getMock();
        $query = $this->getMockBuilder(ParameterBag::class)->disableOriginalConstructor()->getMock();
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->query = $query;
        $repository = $this->getMockBuilder(FileRepository::class)->disableOriginalConstructor()->getMock();
        $serializer = $this->getMockBuilder(FileSerializer::class)->disableOriginalConstructor()->getMock();

        $this->container->expects($this->once())->method('getParameter')->with('tweede_golf_media.max_per_page')
            ->willReturn(10);
        $this->container->expects($this->at(1))->method('get')->with('tweedegolf.repository.file')
            ->willReturn($repository);
        $repository->expects($this->once())->method('findSubset')->willReturn($paginator);
        $this->container->expects($this->at(2))->method('get')->with('tweedegolf.media.file_serializer')
            ->willReturn($serializer);

        $this->controller->indexAction($request);
    }

    public function testCreateAction(): void
    {
        $this->markTestIncomplete();
    }

    public function testDeleteAction(): void
    {
        $this->markTestIncomplete();
    }
}
