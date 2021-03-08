<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator;
use Prophecy\PhpUnit\ProphecyTrait;

class AlphabetValidatorTest extends TestCase
{

    use ProphecyTrait;

    /**
     * @var $model AlphabetValidator
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $spamFilterStatus = $this->prophesize(SpamFilterStatus::class);

        $spamFilterStatus->getBlockedAlphabets()->willReturn(['/\p{Han}+/u','/\p{Cyrillic}+/u']);

        $this->model = new AlphabetValidator($spamFilterStatus->reveal(), 'firstname');
    }

    /**
     * @test
     * @testdox isValid should return true when valid characters are provided
     */
    public function isValidWithValidInput()
    {
        self::assertTrue($this->model->isValid('John Smith'));
    }

    /**
     * @test
     * @testdox isValid should return false when invalid characters are provided
     */
    public function isValidWithInvalidInput()
    {
        self::assertFalse($this->model->isValid('漢字'));
    }

    /**
     * @test
     * @testdox getMessages should return populated array if validation fails
     */
    public function validatorHasMessageAfterFailedValidation()
    {
        $this->model->isValid('漢字');
        self::assertNotEmpty($this->model->getMessages());
    }

    /**
     * @test
     * @testdox getMessages is empty if successful validation is made after failed
     */
    public function validatorHasMessageAfterSubsequentCall()
    {
        $this->model->isValid('漢字');
        $this->model->isValid('John Smith');
        self::assertEmpty($this->model->getMessages());
    }
}
