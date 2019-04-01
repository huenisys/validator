<?php

namespace huenisys\Validator\Tests;

use huenisys\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest
    extends TestCase {

    public $Validator;
    public $goal1Arr;

    public function setUp() :void
    {
        $dataArr1 = [
            'id' => '1',
            'hash' => 'abc123',
            'title' => 'Some Title'
        ];

        $rulesArr1 = [
            'hash' => 'alphanum |stringType',
            'id' => 'numberType',
            'title' => 'stringType|between :10, 20',
        ];

        $this->goal1Arr = [
            "hash" => [
                "alphanum" => [],
                "stringType" => []
            ],
            "id" => [
                "numberType" => []
            ],
            "title" => [
                "stringType" => [],
                "between" => [
                    0 => "10",
                    1 => "20"
                ]
            ]
        ];

        $this->Validator
            = Validator::make($dataArr1,
                $rulesArr1);
    }

    /** @test **/
    public function _normalizeRules_givenLaravelLikeRules_gotConvertedToArray()
    {
        $this->assertEquals($this->goal1Arr, $this->Validator->normalRules);
    }

    /** @test **/
    public function normalizeRules_staticCall()
    {
        $someRules = [
            'age' => 'max:20|numberType'
        ];

        $staticCallGoal = [
            'age' => [
                'max' => [20],
                'numberType' => []
            ]
        ];

        // use the static rules
        $this->assertEquals($staticCallGoal, Validator::normalizeRules($someRules));

        // make sure object signature from earlier wasn't changed
        $this->assertEquals($this->goal1Arr, $this->Validator->normalRules);
    }
}
