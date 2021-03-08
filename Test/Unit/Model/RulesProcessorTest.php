<?php
/**
 * Copyright © Hampus Westman 2021
 * See LICENCE provided with this module for licence details
 *
 * @author     Hampus Westman <hampus.westman@gmail.com>
 * @copyright  Copyright (c) 2021 Hampus Westman
 * @license    MIT License https://opensource.org/licenses/MIT
 * @link       https://github.com/Pr00xxy
 *
 */

namespace PrOOxxy\SpamFilter\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PrOOxxy\SpamFilter\Model\RulesProcessor;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RulesProcessorTest extends TestCase
{

    use ProphecyTrait;

    /**
     * @var $objectManager ObjectManager
     */
    private $objectManager;

    public function setUp(): void
    {
        parent::setUp();
    }

    private function getEmailMock()
    {
        $generatedErrorMessage = ['EMAIL_INVALID_1' => 'email message 1', 'EMAIL_INVALID_2' => 'email message 2'];
        $validator = $this->prophesize(EmailValidator::class);
        $validator->isValid(Argument::any())->willReturn(false);
        $validator->getMessages()->willReturn($generatedErrorMessage);

        return $validator->reveal();
    }

    private function getNameMock()
    {
        $generatedErrorMessage = ['NAME_INVALID_1' => 'name message 1', 'NAME_INVALID_2' => 'name message 2'];
        $validator = $this->prophesize(NameValidator::class);
        $validator->isValid(Argument::any())->willReturn(false);
        $validator->getMessages()->willReturn($generatedErrorMessage);

        return $validator->reveal();
    }

    public function testProcess()
    {

        $collection = [
            'email' => $this->getEmailMock(),
            'name' => $this->getNameMock()
        ];

        $result = $this->getTestClass()->process($collection, ['email' => 'test@example.com', 'name' => 'Clark Olofsson']);

        self::assertCount(4, $result);

    }

    private function getTestClass(): RulesProcessor
    {
        return new RulesProcessor();
    }
}
