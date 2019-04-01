# huenisys/validator

depends on respect/validation for rules and validation

## Usage

Pass an array of data to be validated with matching rules like in Laravel.

```php
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

$this->validator1 = Validator::make(
  $dataArr1,
  $rulesArr1
);

// returns true if passing
$this->validator1->validate();

// returns true if validation fails from above
$this->validator1->fails();

// get errors in markdown
$this->validator1->getErrorsInMd();

// get errors in typical php array bag
$this->validator1->getErrors();
```
