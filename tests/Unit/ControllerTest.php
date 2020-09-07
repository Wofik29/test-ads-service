<?php


namespace Tests\Unit;


use App\Controllers\MainController;
use Core\App;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ControllerTest extends TestCase
{
    /**
     * @param $values
     * @param $params
     * @param $expected
     * @dataProvider validateProvider
     */
    public function testValidation($values, $params, $expected)
    {
        App::getInstance()->instance('request',
            Request::create('/create', 'POST', $values)
        );

        $controller = new MainController();
        $data = $controller->validate($params);

        $this->assertEquals($data, $expected);
    }

    public function validateProvider()
    {
        return [
            'String' => [
                ['name' => 'asdf',],
                ['name' => ['length' => ['max' => 10], 'notBlank']],
                ['name' => 'asdf',],
            ],
            'Number' => [
                ['price' => 100,],
                ['price' => ['positive']],
                ['price' => 100,],
            ],
        ];
    }

    /**
     * @param $values
     * @param $params
     * @param $expected
     * @dataProvider validateErrorProvider
     * @throws
     */
    public function testValidateError($values, $params, $expected)
    {
        $this->expectException(BadRequestHttpException::class);
        App::getInstance()->instance('request',
            Request::create('/create', 'POST', $values)
        );

        $controller = new MainController();
        try {
            $controller->validate($params);
        } catch (Exception $ex) {
            $this->assertEquals($expected, json_decode($ex->getMessage(), true));
            throw $ex;
        }
    }

    public function validateErrorProvider()
    {
        return [
            'String too long' => [
                ['name' => 'asdfasdfasdfasdf',],
                ['name' => ['length' => ['max' => 10]]],
                ['name' => ["This value is too long. It should have 10 characters or less."],],
            ],
            'Number is negative' => [
                ['price' => -100,],
                ['price' => ['positive']],
                ['price' => ['This value should be positive.'],],
            ],
        ];
    }

}