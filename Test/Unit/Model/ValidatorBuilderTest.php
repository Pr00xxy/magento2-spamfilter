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

use InvalidArgumentException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidatorFactory;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidatorFactory;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidatorFactory;
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
     * @var MockObject
     */
    private $emailValidatorFactoryMock;

    /**
     * @var MockObject
     */
    private $nameValidatorFactoryMock;

    /**
     * @var MockObject
     */
    private $alphabetValidatorFactoryMock;

    public function setUp(): void
    {
        $this->emailValidatorFactoryMock = $this->prophesize(EmailValidatorFactory::class);
        $this->nameValidatorFactoryMock = $this->prophesize(NameValidatorFactory::class);
        $this->alphabetValidatorFactoryMock = $this->prophesize(AlphabetValidatorFactory::class);

        $this->emailValidatorFactoryMock->create()->willReturn(
            $this->prophesize(EmailValidator::class)->reveal()
        );
        $this->nameValidatorFactoryMock->create()->willReturn(
            $this->prophesize(NameValidator::class)->reveal()
        );
        $this->alphabetValidatorFactoryMock->create()->willReturn(
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
        $this->expectException(InvalidArgumentException::class);
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
        $this->expectException(InvalidArgumentException::class);
        $class->getNewNameValidator('invalid');
    }

    private function getTestClass(array $dependencies = []): ValidatorBuilder
    {
        /** @var $class ValidatorBuilder */
        $class = $this->objectManager->getObject(
            ValidatorBuilder::class,
            [
                'emailValidatorFactory' => $this->emailValidatorFactoryMock->reveal(),
                'alphabetValidatorFactory' => $this->alphabetValidatorFactoryMock->reveal(),
                'nameValidatorFactory' => $this->nameValidatorFactoryMock->reveal()
            ]
        );
        return $class;
    }
}
