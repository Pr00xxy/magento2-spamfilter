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

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;
use PrOOxxy\SpamFilter\Model\ValidatorBuilder;
use Prophecy\PhpUnit\ProphecyTrait;

class ValidatorBuilderTest extends TestCase
{

    use ProphecyTrait;

    /**
     * @var $objectManager ObjectManager
     */
    private $objectManager;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $emailValidatorFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $nameValidatorFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $alphabetValidatorFactoryMock;

    public function setUp(): void
    {
        $this->emailValidatorFactoryMock = $this->getMockBuilder(\PrOOxxy\SpamFilter\Model\Validator\EmailValidatorFactory::class)->setMethods(['create'])->getMock();
        $this->nameValidatorFactoryMock = $this->getMockBuilder(\PrOOxxy\SpamFilter\Model\Validator\NameValidatorFactory::class)->setMethods(['create'])->getMock();
        $this->alphabetValidatorFactoryMock = $this->getMockBuilder(\PrOOxxy\SpamFilter\Model\Validator\AlphabetValidatorFactory::class)->setMethods(['create'])->getMock();

        $this->emailValidatorFactoryMock->method('create')->willReturn(
            $this->prophesize(EmailValidator::class)->reveal()
        );
        $this->nameValidatorFactoryMock->method('create')->willReturn(
            $this->prophesize(NameValidator::class)->reveal()
        );
        $this->alphabetValidatorFactoryMock->method('create')->willReturn(
            $this->prophesize(AlphabetValidator::class)->reveal()
        );

        $this->objectManager = new ObjectManager($this);
    }

    /**
     * @test
     * @testdox getNewMailValidator should throw Exception if not passed valid input string
     * @see \PrOOxxy\SpamFilter\Model\ValidatorBuilder::getNewNameValidator()
     */
    public function getNewNameValidatorWithException()
    {
        $class = $this->getTestClass();
        $this->expectException(\InvalidArgumentException::class);
        $class->getNewNameValidator('invalid');
    }

    /**
     * @test
     * @testdox getNewMailValidator should throw Exception if not passed valid input string
     * @see \PrOOxxy\SpamFilter\Model\ValidatorBuilder::getNewEmailValidator()
     */
    public function getNewEmailValidatorWithException()
    {
        $class = $this->getTestClass();
        $this->expectException(\InvalidArgumentException::class);
        $class->getNewNameValidator('invalid');
    }

    private function getTestClass(array $dependencies = []): ValidatorBuilder
    {
        /** @var $class ValidatorBuilder */
        $class = $this->objectManager->getObject(
            ValidatorBuilder::class,
            [
                'emailValidatorFactory' => $this->emailValidatorFactoryMock,
                'alphabetValidatorFactory' => $this->alphabetValidatorFactoryMock,
                'nameValidatorFactory' => $this->nameValidatorFactoryMock
            ]
        );
        return $class;
    }
}
