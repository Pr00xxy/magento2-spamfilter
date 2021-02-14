<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PharIo\Manifest\Email;
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

        $this->model = new EmailValidator($this->spamFilterStatus->reveal(), 'email');
    }

    /**
     * @test
     * @dataProvider isValidDataProvider
     */
    public function isValid(string $email, bool $assertion)
    {
        $this->spamFilterStatus->getBlockedAddresses()->willReturn(['*@blocked.com', '*@*.xyz', 'blocked@*.*']);

        self::assertEquals($this->model->isValid($email), $assertion);
    }

    public function isValidDataProvider(): array
    {
        return [
            [
                'email' => 'something@blocked.com',
                'assertion' => false
            ],
            [
                'email' => 'allowed@gmail.com',
                'assertion' => true
            ],
            [
                'email' => 'blocked@gmail.xyz',
                'assertion' => false
            ]
        ];
    }

}
