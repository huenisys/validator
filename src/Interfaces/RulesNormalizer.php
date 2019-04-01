<?php

namespace huenisys\Validator\Interfaces;

interface RulesNormalizer {

    /**
     * Converts string rules to array
     *
     * @param Array $rules
     * @return Array the converted rules
     */
    public static function normalizeRules($rules);
}
