<?php


namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
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

        $objectManager = new ObjectManager($this);

        $spamFilterStatus = $this->prophesize(SpamFilterStatus::class);

        $spamFilterStatus->getBlockedAlphabets()->willReturn(['/\p{Han}+/u','/\p{Cyrillic}+/u']);

        $this->model = $objectManager->getObject(
            AlphabetValidator::class,
            [
                'config' => $spamFilterStatus->reveal(),
                'field' => 'firstname'
            ]
        );
    }

    /**
     * @test
     */
    public function isValid()
    {
        self::assertEquals(true, $this->model->isValid('Knut kragballe'));
    }

    /**
     * @test
     */
    public function notValid()
    {
        self::assertEquals(false, $this->model->isValid('漢字'));
        self::assertArrayHasKey('CHARSET_INVALID', $this->model->getMessages());
    }
}
