<?php

namespace huenisys\Validator\Interfaces;

interface RulesNormalizer {

    /**
     * Converts array of string rules
     * i.e. rules separated by |
     * params by ,
     * e.g. ['age' => 'number|between:10,20']
     * gets converted to [ age =>
     *  ['number' => [] , 'between' => [10, 20]]
     * ]
     *
     * @param Array $rules
     * @return Array the converted rules
     */
    public static function normalizeRules($rules);
}
