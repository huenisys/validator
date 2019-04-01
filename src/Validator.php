<?php

namespace huenisys\Validator;

class Validator extends ValidatorAbstract
{
    public $data = [];
    public $rules = [];
    public $normalRules = [];

    protected function _validate($key, $constraintsArr)
    {
        // dump(constraintsArr);
    }
}
