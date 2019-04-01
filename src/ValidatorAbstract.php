<?php

namespace huenisys\Validator;

use Respect\Validation\Rules;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

abstract class ValidatorAbstract
    implements Interfaces\Validator,
        Interfaces\RulesNormalizer {

    use Traits\RulesNormalizer;

    /**
     * The data to validate
     *
     * $var Array
     */
    protected $data;

    /**
     * The data to validate
     *
     * $var Array
     */
    protected $rules;

    /**
     * Normalized rules
     *
     * $var Array
     */
    protected $normalRules;


    /**
     * Save exception
     *
     * $var NestedValidationException|null
     */
    protected $exception = null;

    /**
     * Creates the validator instance
     *
     * @param Array $data
     * @param Array $rules
     */
    final function
         __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;

        $this->_normalizeRules();
    }

    final static function make($data, $rules)
    {
        return new static($data, $rules);
    }

    protected function _normalizeRules()
    {
        $this->normalRules = static::normalizeRules($this->rules);
    }

    final public function getData($key = null)
    {
        return (is_null($key) or $key === '')
            ? $this->data
            : $this->data[$key];
    }

    final public function getRules($isRaw = false)
    {
        return $isRaw
            ? $this->rules
            : $this->normalRules;
    }

    public function validate()
    {
        $keyValidators = [];
        $rulesNamespace = "Respect\\Validation\\Rules\\";

        foreach ($this->getRules() as $dataKey => $rulesArr)
        {
            // create collection of rules for this key
            $ruleObjectsArrForCurrentKey = array_map(
                function($ruleParams, $ruleKey) use($rulesNamespace)
                {
                    $ruleClassName = $rulesNamespace.ucfirst($ruleKey);
                    return new $ruleClassName(...$ruleParams);
                },
                $rulesArr,
                array_keys($rulesArr)
            );

            $keyValidator
                = (new Rules\AllOf(...$ruleObjectsArrForCurrentKey))
                    ->setName(ucfirst($dataKey));

            $keyValidators[] = new Rules\Key($dataKey, $keyValidator);
        }

        $schemaValidator = v::allOf(...$keyValidators);

        if ($schemaValidator->validate($this->getData()))
        {
            // clear error
            $this->exception = null;
            return true;
        }
        else {
            // expose errors
            try {
                $schemaValidator->assert($this->getData());
            } catch(NestedValidationException $exception) {
                $this->exception = $exception;
                // var_dump($exception->getMessages());
                // throw $exception;
            }
        }
    }

    public function fails()
    {
        return is_array($this->getErrors());
    }

    public function getErrors()
    {
        return optional($this->exception)->getMessages();
    }

    public function getErrorsInMd()
    {
        return optional($this->exception)->getFullMessage();
    }
}
