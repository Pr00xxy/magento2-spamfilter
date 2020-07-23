<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;

class EmailValidatorTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $spamFilterStatus;

    public function setup()
    {
        parent::setUp();

        $this->spamFilterStatus = $this->prophesize(SpamFilterStatus::class);
    }

    /**
     * @test
     * @dataProvider isValidDataProvider
     */
    public function isValid(string $email, bool $assertion)
    {
        $this->spamFilterStatus->getBlockedAddresses()->willReturn(['*@blocked.com', '*@*.xyz', 'blocked@*.*']);

        $this->assertEquals($this->getTestClass()->isValid($email), $assertion);
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

    private function getTestClass(array $dependencies = [])
    {
        $objectManager = new ObjectManager($this);
        return $objectManager->getObject(
            \PrOOxxy\SpamFilter\Model\Validator\EmailValidator::class,
            [
            'config' => $this->spamFilterStatus->reveal(),
            'field' => 'email'
            ]
        );
    }
}
