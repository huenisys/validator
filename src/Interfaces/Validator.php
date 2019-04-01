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
}
