<?php

namespace huenisys\Validator\Interfaces;

interface Validator{

    /**
     * Creates the validator instance
     *
     * @param Array $data
     * @param Array $rules
     */
    public static function make($data, $rules);

    /**
     * Get raw data
     *
     * @param String|null
     * @return Array
     */
    public function getData($key = null);

    /**
     * Get either the raw or normalized rules
     * Defaults to normalRules
     *
     * @param Boolean $isRaw
     * @return Array
     */
    public function getRules($isRaw = false);

    /**
     * Get error bag
     *
     * @return Array|null
     */
    public function getErrors();

    /**
     * Get errors in markdown
     *
     * @return String|null
     */
    public function getErrorsInMd();

    /**
     * Returns true if validation fails
     *
     * @return Boolean
     */
    public function fails();
}
