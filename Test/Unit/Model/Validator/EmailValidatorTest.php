<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use Prophecy\PhpUnit\ProphecyTrait;

class EmailValidatorTest extends TestCase
{

    use ProphecyTrait;

    /**
     * @var MockObject
     */
    private $spamFilterStatus;

    /**
     * @var EmailValidator
     */
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->spamFilterStatus = $this->prophesize(SpamFilterStatus::class);
        $this->spamFilterStatus->getBlockedAddresses()->willReturn(['*@blocked.com', '*@*.xyz', 'blocked@*.*']);

        $this->model = new EmailValidator($this->spamFilterStatus->reveal(), 'email');
    }

    public function testIsValidWithBlockedDomain()
    {
        self::assertFalse($this->model->isValid('something@blocked.com'));
    }

    public function testIsValidWithBlockedTopLevel()
    {
        self::assertFalse($this->model->isValid('something@gmail.xyz'));
    }

    public function testIsValidWithBlockedWildCard()
    {
        self::assertFalse($this->model->isValid('blocked@gmail.com'));
    }

}
