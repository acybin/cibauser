<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use App\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\ResponseEvent;

/*function render_template($request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    //include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    return new Response('aa');
}*/

require_once dirname(__DIR__) . '/vendor/autoload.php';

$routes = new RouteCollection();

//$routes->add('hello', new Route('/hello/{name}', ['name' => null, '_controller' => 'App\Funca::render']));
$routes->add('hello', new Route('/', ['_controller' => 'App\Funca::render']));
$routes->add('bye', new Route('/bye'));

$request = Request::createFromGlobals();

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$dispatcher = new EventDispatcher();
//$dispatcher->addListener('response', function (ResponseEvent $event) {
    //$response = $event->getResponse();

    /*if ($response->isRedirection()
        || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
        || 'html' !== $event->getRequest()->getRequestFormat()
    ) {
        return;
    }

    $response->setContent($response->getContent().'GA CODE');
});*/

$framework = new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();



