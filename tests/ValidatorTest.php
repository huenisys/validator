<?php

namespace huenisys\Validator\Tests;

use huenisys\Validator\Validator;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\NestedValidationException;

class ValidatorTest
    extends TestCase {

    public $Validator;
    public $expected1Arr;

    public function setUp() :void
    {
        $dataArr1 = [
            'id' => '1',
            'hash' => 'abc123',
            'title' => 'Some Title'
        ];

        $rulesArr1 = [
            'id' => 'numeric',
            'hash' => 'stringType|alnum',
            'title' => 'stringType|between :10, 20',
        ];

        $this->expected1Arr = [
            "id" => [
                "numeric" => []
            ],
            "hash" => [
                "stringType" => [],
                "alnum" => [],
            ],
            "title" => [
                "stringType" => [],
                "between" => [
                    0 => "10",
                    1 => "20"
                ]
            ]
        ];

        $this->validator1
            = Validator::make($dataArr1,
                $rulesArr1);
    }

    /** @test **/
    public function make_laravelLikeRules_getsConvertedToArray()
    {
        $this->assertEquals($this->expected1Arr, $this->validator1->getRules());
    }

    /** @test **/
    public function normalizeRules_staticCall_doesNotAffectExistingInstance()
    {
        $someRules = [
            'age' => 'numeric|max:20'
        ];

        $staticCallExpected = [
            'age' => [
                'numeric' => [],
                'max' => [20],
            ]
        ];

        // use the static rules
        $this->assertEquals($staticCallExpected, Validator::normalizeRules($someRules));

        // make sure object signature from earlier wasn't changed
        $this->assertEquals($this->expected1Arr, $this->validator1->getRules());
    }

    /** @test **/
    public function normalizeRules_staticCall_trailingSpacesGetsTrimmed()
    {
        $someRules = [
            'age' => 'numType | between: 6, 100'
        ];

        $staticCallExpected = [
            'age' => [
                'numType' => [],
                'between' => [6,100]
            ]
        ];

        // use the static rules
        $this->assertEquals($staticCallExpected, Validator::normalizeRules($someRules));
    }

    /** @test **/
    public function normalizeRules_staticCall_convertStringNulltoPhpNull()
    {
        $someRules = [
            'text' => 'stringType|length:null,100'
        ];

        $staticCallExpected = [
            'text' => [
                'stringType' => [],
                'length' => [null, 100]
            ]
        ];

        // use the static rules
        $this->assertEquals($staticCallExpected, Validator::normalizeRules($someRules));
    }

    /** @test **/
    public function getData()
    {
        $data = [
            'age' => '6'
        ];

        $this->assertEquals(
            $data, Validator::make($data, [])->getData());

        $this->assertEquals(
            6, Validator::make($data, [])->getData('age'));
    }

    /** @test **/
    public function getRules_raw()
    {
        $rulesRaw = [
            'age' => 'numType | between: 6, 100'
        ];

        $this->assertEquals(
            $rulesRaw, Validator::make([], $rulesRaw)->getRules(true));
    }

    /** @test **/
    public function getRules_normal()
    {
        $rulesRaw = [
            'age' => 'numType | between: 6, 100'
        ];

        $rulesNormal = [
            'age' => [
                'numType' => [],
                'between' => [6, 100]
            ]
        ];

        $this->assertEquals(
            $rulesNormal, Validator::make([], $rulesRaw)->getRules());
    }

    /** @test **/
    public function validate_someRulesCheck()
    {
        $dataArr = [
            'id' => '1',
            'hash' => 'abc123',
            'title' => 'Some Title'
        ];

        $rulesArr = [
            'id' => 'numeric',
            'hash' => 'stringType|alnum',
            'title' => 'stringType|length:null,15',
        ];

        $this->assertTrue(
            Validator::make($dataArr, $rulesArr)->validate()
        );
    }

    /** @test **/
    public function validate_idIsNotNumeric_errorMessages()
    {
        $dataArr = [
            'id' => 'a'
        ];

        $rulesArr = [
            'id' => 'numeric'
        ];

        $validator
            = Validator::make($dataArr, $rulesArr);

        $validator->validate();

        $this->assertIsArray($validator->getErrors());

        $this->assertTrue($validator->fails());

        $this->assertStringContainsString(
            'Id must be numeric',
            $validator->getErrors()[0]
            );

        $this->assertStringContainsString(
            '- Id must be numeric',
            $validator->getErrorsInMd()
            );

        // var_dump($validator->getErrorsInMd());
    }

    public function validate_idIsNotNumeric_expectException()
    {
        $this->expectException(
            NestedValidationException::class
        );

        $dataArr = [
            'id' => 'a'
        ];

        $rulesArr = [
            'id' => 'numeric'
        ];

        $validator
            = Validator::make($dataArr, $rulesArr);

        $validator->validate();

    }
}
