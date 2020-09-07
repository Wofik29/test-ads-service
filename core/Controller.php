<?php


namespace Core;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class Controller
{
    public function callAction(string $method, array $parameters): Response
    {
        try {
            $result = call_user_func_array([$this, $method], $parameters);
        } catch (\Throwable $ex) {
            // report

            if ($ex instanceof HttpException) {
                $status = $ex->getStatusCode();
            } else {
                $status = 500;
            }

            $result = new Response($ex->getMessage(), $status, ['Content-Type' => 'application/json' ]);
        }

        if ($result instanceof Response) {
            $response = $result;
        } else {
            $response = new Response();

            //TODO Для таких вещей нужны middleware
            if (is_array($result)) {
                $response->setContent(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
            }
        }

        return $response->prepare(App::getInstance()->get('request'));
    }

    /**
     * @param string $method
     * @param array $parameters
     * @throws \BadMethodCallException
     */
    public function __call(string $method, array $parameters)
    {
        throw new \BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    /**
     * @param array $params
     * @return array
     * @throws BadRequestHttpException
     */
    public function validate(array $params): array
    {
        $validate = new Validator();
        ['result' => $result, 'errors' => $errors] = $validate->validate($params);

        if (count($errors)) {
            throw new BadRequestHttpException(json_encode($errors));
        }

        return $result;
    }
}