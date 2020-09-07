<?php


namespace Core;


use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    protected ValidatorInterface $handle;
    protected Request $request;

    public function __construct()
    {
        $this->handle = Validation::createValidator();
        $this->request = App::getInstance()->get('request');
    }

    /**
     * @param array $params
     * @return array ['result' => [], 'errors' => []]
     */
    public function validate(array $params): array
    {
        $result = [];
        $errors = [];

        foreach ($params as $name => $rules) {
            $constraints = $this->getConstraints($rules);
            $value = $this->getValue($name);

            if ($value == null)
                continue;

            $violations = $this->handle->validate($value, $constraints);
            if ($violations->count() == 0) {
                $result[$name] = $value;
            } else {
                foreach ($violations as $violation) {
                    /** @var $violation ConstraintViolation */
                    $errors[$name][] = $violation->getMessage();
                }
            }
        }

        return compact('result', 'errors');
    }

    protected function getValue(string $name)
    {
        $value = $this->request->get($name);

        if ($value == null && $this->request->files->has($name)) {
            $value = $this->request->files->get($name);
        }

        return $value;
    }


    protected function getConstraints(array $rules): array
    {
        $path = 'Symfony\Component\Validator\Constraints\\';
        $result = [];
        foreach ($rules as $key => $rule) {
            if (is_array($rule)) {
                $class = $key;
            } else {
                $class = $rule;
                $rule = [];
            }

            $reflection = new \ReflectionClass($path . Str::ucfirst($class));
            $result[] = $reflection->newInstance($rule);
        }

        return $result;
    }
}