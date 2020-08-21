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

    /**
     * @var $objectManager ObjectManager
     */
    private $objectManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->objectManager = new ObjectManager($this);
    }

    public function testGetBlockedAlphabets()
    {
        $configMock = $this->prophesize(ScopeConfigInterface::class);
        $configMock->getValue(Argument::cetera())->willReturn("/\p{Cyrillic}+/u,/\p{Han}+/u");
        $class = $this->getTestClass(['config' => $configMock->reveal(), 'scope' => 'test']);
        self::assertEquals(["/\p{Cyrillic}+/u","/\p{Han}+/u"], $class->getBlockedAlphabets());
    }

    public function testGetBlockedAddresses()
    {
        $configMock = $this->prophesize(ScopeConfigInterface::class);
        $configMock->getValue(Argument::cetera())->willReturn("*@gmail.com,*@*.ru");
        $class = $this->getTestClass(['config' => $configMock->reveal(), 'scope' => 'test']);
        self::assertEquals(["*@gmail.com","*@*.ru"], $class->getBlockedAddresses());
    }

    private function getTestClass(array $dependencies): SpamFilterStatus
    {
        return $this->objectManager->getObject(SpamFilterStatus::class, $dependencies);
    }
}
