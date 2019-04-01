<?php

namespace huenisys\Validator\Traits;

trait RulesNormalizer {

    public static function normalizeRules($rules)
    {
        $normalRules = [];

        // normalize it
        foreach ($rules as $dataKey => $constraintStr)
        {
            // if string of constraints sep by |, explode
            if (is_string($constraintStr))
            {
                $rules0 = static::_strToRulesArr($constraintStr);

                $rules1 = static::_strToFxnPartsArray($rules0);

                $normalRules[trim($dataKey)] = $rules1;
            }
        }

        return $normalRules;
    }

    protected static function _strToRulesArr(String $constraintStr)
    {
        return array_map('trim', explode('|', $constraintStr));
    }

    protected static function _strToFxnPartsArray(Array $rules0)
    {
        $rules1 = [];
        foreach ($rules0 as $ruleStrMaybeWithColon)
        {
            $fnParts = array_map('trim', explode(':', $ruleStrMaybeWithColon));
            $fnName = $fnParts[0];
            $fnParamsMaybeWithComma = $fnParts[1] ?? null;

            $rules1[$fnName]
                = is_null($fnParamsMaybeWithComma)
                    ? []
                    : static::_strToParamsArray($fnParamsMaybeWithComma);
        }
        return $rules1;
    }

    protected static function _strToParamsArray(String $fnParamsMaybeWithComma)
    {
        return array_map('trim', explode(',', $fnParamsMaybeWithComma));
    }
}
