<?php


namespace Core;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class HttpKernel implements HttpKernelInterface
{
    protected string $controllerMethod;

    protected array $middleware = [];

    public function handle(Request $request, int $type = self::MASTER_REQUEST, bool $catch = true)
    {
        App::getInstance()->instance('request', $request);

        // Тут можно сделать проход по middleware

        $controller = $this->resolveRoute();
        $result = $controller->callAction($this->getControllerMethod(), ['request' => $request]);

        return $result;

    }

    private function getControllerMethod()
    {
        return $this->controllerMethod;
    }

    private function resolveRoute(): Controller
    {
        $routes = App::getInstance()->get('routes');
        $request = App::getInstance()->get('request');
        $context = (new RequestContext())->fromRequest($request);

        $matcher = new UrlMatcher($routes, $context);
        $route = $matcher->match($request->getPathInfo());
        $this->controllerMethod = $route['_controller'][1];

        return new $route['_controller'][0];
    }


}