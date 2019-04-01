<?php

namespace huenisys\Validator;

abstract class ValidatorAbstract
    implements Interfaces\Validator, Interfaces\RulesNormalizer {

    use Traits\RulesNormalizer;

    /**
     * The data to validate
     *
     * $var Array
     */
    public $data;

    /**
     * The data to validate
     *
     * $var Array
     */
    public $rules;

    /**
     * Normalized rules
     *
     * $var Array
     */
    public $normalRules;

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
}
