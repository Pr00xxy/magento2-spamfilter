<?php


namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;


class AlphabetValidatorTest extends TestCase
{

    /**
     * @var $objectManager
     */
    private $objectManager;

    /**
     * @var $model \PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator
     */
    private $model;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $spamFilterStatus;

    public function setup()
    {
        parent::setUp();

        $this->objectManager = new ObjectManager($this);

        $this->spamFilterStatus = $this->getMockBuilder(SpamFilterStatus::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBlockedAlphabets'])->getMock();

        $this->spamFilterStatus->method('getBlockedAlphabets')->willReturn(['/\p{Han}+/u','/\p{Cyrillic}+/u']);

        $this->model = $this->objectManager->getObject(
            \PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator::class,
            [
                'config' => $this->spamFilterStatus,
                'field' => 'firstname'
            ]
        );
    }

    /**
     * @test
     */
    public function isValid()
    {
        $this->assertEquals(true, $this->model->isValid('Knut kragballe'));
    }

    /**
     * @test
     */
    public function notValid()
    {
        $this->assertEquals(false, $this->model->isValid('漢字'));
        $this->assertArrayHasKey('CHARSET_INVALID', $this->model->getMessages());
    }
}
