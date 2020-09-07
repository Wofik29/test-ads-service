<?php
require __DIR__.'/../vendor/autoload.php';

$app = new \Core\App(__DIR__);

/** @var \Symfony\Component\HttpKernel\HttpKernelInterface $kernel */
$kernel = $app->get('kernel');

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();
