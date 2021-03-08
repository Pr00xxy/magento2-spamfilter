<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;

class NameValidatorTest extends TestCase
{

    /**
     * @var $model NameValidator
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new NameValidator('firstname');
    }

    /**
     * @test
     * @dataProvider isValidDataProviderTrue
     */
    public function isValidShouldReturnTrue($string)
    {
        self::assertTrue($this->model->isValid($string));
    }

    /**
     * @test
     * @dataProvider isValidDataProviderFalse
     */
    public function isValidShouldReturnFalse($string)
    {
        self::assertFalse($this->model->isValid($string));
    }

    public function isValidDataProviderFalse()
    {
        return [
            'Should detect FQDN' => ['facebook.com'],
            'Should detect hidden full url' => ['stringwithhttps://google.comstring'],
            'Should detect unfinished url' => ['string with https:// unfinished link'],
        ];
    }

    public function isValidDataProviderTrue()
    {
        return [
            'Should not detect non existing' => ['Johnny Bravo'],
            'Should not detect fragments' => ['string . with .com stuff']
        ];
    }
}
