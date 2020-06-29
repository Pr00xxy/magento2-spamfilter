<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;

class EmailValidatorTest extends TestCase
{

    /**
     * @var $model \PrOOxxy\SpamFilter\Model\Validator\EmailValidator
     */
    private $model;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $spamFilterStatus;

    public function setup()
    {
        parent::setUp();

        $objectManager = new ObjectManager($this);

        $this->spamFilterStatus = $this->getMockBuilder(SpamFilterStatus::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBlockedAddresses'])->getMock();

        $this->model = $objectManager->getObject(
            \PrOOxxy\SpamFilter\Model\Validator\EmailValidator::class,
            [
                'config' => $this->spamFilterStatus,
                'field' => 'email'
            ]
        );
    }

    /**
     * @test
     * @dataProvider isValidDataProvider
     */
    public function isValid(string $email, bool $assertion)
    {

        $this->spamFilterStatus->method('getBlockedAddresses')
            ->willReturn(['*@blocked.com', '*@*.xyz', 'blocked@*.*']);

        $this->assertEquals($this->model->isValid($email), $assertion);

        if (!$assertion) {
            $this->assertNotEmpty($this->model->getMessages());
        }
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
