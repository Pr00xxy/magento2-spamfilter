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

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class SpamFilterStatusTest extends TestCase
{

    use ProphecyTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetBlockedAlphabets()
    {
        $configMock = $this->prophesize(ScopeConfigInterface::class);
        $configMock->getValue(Argument::cetera())->willReturn("/\p{Cyrillic}+/u,/\p{Han}+/u");
        $class = $this->getTestClass([$configMock->reveal(), 'test']);
        self::assertEquals(["/\p{Cyrillic}+/u","/\p{Han}+/u"], $class->getBlockedAlphabets());
    }

    public function testGetBlockedAddresses()
    {
        $configMock = $this->prophesize(ScopeConfigInterface::class);
        $configMock->getValue(Argument::cetera())->willReturn("*@gmail.com,*@*.ru");
        $class = $this->getTestClass([$configMock->reveal(), 'test']);
        self::assertEquals(["*@gmail.com","*@*.ru"], $class->getBlockedAddresses());
    }

    private function getTestClass(array $dependencies): SpamFilterStatus
    {
        return new SpamFilterStatus(...$dependencies);
    }
}
