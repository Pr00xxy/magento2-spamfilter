<?php
/**
 * Copyright © Hampus Westman 2020
 * See LICENCE provided with this module for licence details
 *
 * @author     Hampus Westman <hampus.westman@gmail.com>
 * @copyright  Copyright (c)  {year} Hampus Westman
 * @license    MIT License https://opensource.org/licenses/MIT
 * @link       https://github.com/Pr00xxy
 *
 */

namespace PrOOxxy\SpamFilter\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PrOOxxy\SpamFilter\Model\RulesProcessor;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use Prophecy\Argument;

class RulesProcessorTest extends TestCase
{

    /**
     * @var $objectManager ObjectManager
     */
    private $objectManager;

    public function setup()
    {
        parent::setUp();

        $this->objectManager = new ObjectManager($this);
    }

    public function testProcess()
    {

        $generatedErrorMessage = ['INVALID_1' => 'error message 1', 'INVALID_2' => 'error message 2'];
        $expectedErrorMessageResult = array_values($generatedErrorMessage);

        $validator = $this->prophesize(EmailValidator::class);
        $validator->isValid(Argument::any())->willReturn(false);
        $validator->getMessages()->willReturn($generatedErrorMessage);

        $collection = ['email' => $validator->reveal()];

        $result = $this->getTestClass()->process($collection, ['email' => 'test@example.com']);

        $this->assertEquals($expectedErrorMessageResult, $result);
    }

    private function getTestClass(array $dependencies = []): RulesProcessor
    {
        return $this->objectManager->getObject(RulesProcessor::class, $dependencies);
    }
}
