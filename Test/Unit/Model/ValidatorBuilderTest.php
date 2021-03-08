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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;
use PrOOxxy\SpamFilter\Model\Validator\ValidatorFactory;
use PrOOxxy\SpamFilter\Model\ValidatorBuilder;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class ValidatorBuilderTest extends TestCase
{

    use ProphecyTrait;

    private $validatorFactoryMock;

    public function setUp(): void
    {
        $this->validatorFactoryMock = $this->prophesize(ValidatorFactory::class);
        $this->spamFilterStatusMock = $this->prophesize(SpamFilterStatus::class);
    }

    /**
     * @test
     * @testdox getNewMailValidator should throw Exception if not passed valid input string
     * @see \PrOOxxy\SpamFilter\Model\ValidatorBuilder::getNewNameValidator()
     */
    public function getNewNameValidatorWithException()
    {
        $class = $this->getTestClass();
        $this->expectException(InvalidArgumentException::class);
        $class->getNewNameValidator('invalid');
    }

    /**
     * @test
     * @testdox getNewMailValidator should return EmailValidator
     * @see \PrOOxxy\SpamFilter\Model\ValidatorBuilder::getNewNameValidator()
     */
    public function getNewNameValidator()
    {
        $this->validatorFactoryMock->create(NameValidator::class, Argument::any())->willReturn(
            $this->prophesize(NameValidator::class)->reveal()
        );

        $class = $this->getTestClass();
        $validator = $class->getNewNameValidator('name');

        self::assertInstanceOf(NameValidator::class, $validator);
    }

    /**
     * @test
     * @testdox getNewMailValidator should throw Exception if passed invalid string
     * @see \PrOOxxy\SpamFilter\Model\ValidatorBuilder::getNewEmailValidator()
     */
    public function getNewEmailValidatorWithException()
    {
        $class = $this->getTestClass();
        $this->expectException(InvalidArgumentException::class);
        $class->getNewNameValidator('invalid');
    }

    /**
     * @test
     * @testdox getNewEmailValidator should return EmailValidator
     * @see \PrOOxxy\SpamFilter\Model\ValidatorBuilder::getNewEmailValidator()
     */
    public function getNewEmailValidator()
    {
        $this->validatorFactoryMock->create(EmailValidator::class, Argument::any())->willReturn(
            $this->prophesize(EmailValidator::class)->reveal()
        );
        $class = $this->getTestClass();
        $validator = $class->getNewEmailValidator('email');

        self::assertInstanceOf(EmailValidator::class, $validator);
    }


    private function getTestClass(): ValidatorBuilder
    {
        return new ValidatorBuilder(
            $this->validatorFactoryMock->reveal(),
            $this->spamFilterStatusMock->reveal()
        );
    }
}
