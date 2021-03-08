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

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class SpamFilterStatusTest extends TestCase
{

    use ProphecyTrait;

    /**
     * @var ObjectProphecy
     */
    private $configMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->configMock = $this->prophesize(ScopeConfigInterface::class);

        $this->configMock->getValue('spamfilter/test/alphabet_blocked_alphabets', Argument::any())->willReturn("/\p{Cyrillic}+/u,/\p{Han}+/u");

        $this->configMock->getValue('spamfilter/general/email_blocked_addresses', Argument::any())->willReturn("*@gmail.com,*@*.ru");

    }

    public function testGetBlockedAlphabets(): void
    {
        $class = $this->getTestClass([$this->configMock->reveal(), 'test']);
        self::assertEquals(["/\p{Cyrillic}+/u","/\p{Han}+/u"], $class->getBlockedAlphabets());
    }

    /**
     * @test
     * @testdox getBlockedAlphabets should return empty array if no languages have been configured
     */
    public function getBlockedAlphabetsShouldReturnEmptyIfNotConfigured()
    {
        $this->configMock->getValue('spamfilter/test/alphabet_blocked_alphabets', Argument::any())->willReturn(null);
        $class = $this->getTestClass([$this->configMock->reveal(), 'test']);
        self::assertEmpty($class->getBlockedAlphabets());
    }

    public function testGetBlockedAddresses(): void
    {
        $class = $this->getTestClass([$this->configMock->reveal(), 'test']);
        self::assertEquals(["*@gmail.com","*@*.ru"], $class->getBlockedAddresses());
    }

    private function getTestClass(array $dependencies): SpamFilterStatus
    {
        return new SpamFilterStatus(...$dependencies);
    }
}
