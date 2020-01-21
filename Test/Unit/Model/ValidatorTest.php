<?php
/**
 *
 * ValidatorTest.php
 *
 * This file is part of Foobar.
 *
 * {module_name} is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * {module_name} is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with {module_name}.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category   Pr00xxy
 * @package    {package_name}
 * @author     Hampus Westman <hampus.westman@gmail.com>
 * @copyright  Copyright (c) 2020 Hampus Westman
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GPLv3.0
 *
 */

namespace PrOOxxy\SpamFilter\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\Validator\DefaultValidator;


class ValidatorTest extends TestCase
{

    /**
     * @var $objectManager
     */
    private $objectManager;

    /**
     * @var $model DefaultValidator
     */
    private $model;

    private $filterStatus;

    public function setup()
    {
        parent::setUp();

        $this->objectManager = new ObjectManager($this);

        $this->filterStatus = $this->getMockBuilder(SpamFilterStatus::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getBlockedAddresses',
                    'getBlockedAlphabets',
                    'getAlphabetByString'
                ]
            )
            ->getMock();

        $this->model = $this->objectManager->getObject(
            DefaultValidator::class,
            [
                'scope' => 'create_account',
                'status' => $this->filterStatus
            ]
        );
    }

    /**
     * @dataProvider StringHasLinkDataProvider
     * @see          \PrOOxxy\SpamFilter\Model\Validator\DefaultValidator::stringHasLink
     */
    public function testStringHasLink(string $string, bool $assertion)
    {
        $stringWithLink = $this->model->stringHasLink($string);
        $this->assertEquals($stringWithLink, $assertion);
    }

    public function stringHasLinkDataProvider(): array
    {
        return [
            'Explicit url' => ['https://devdocs.magento.com/guides/v2.3/mtf/mtf_entities/mtf_testcase.html', true],
            'Partial urls' => ['This link contains http and :// and some www but does not trigger .com', false],
            'Hidden Url' => ['Thisstringcontainsahiddenhttps://facebook.com/linkwithoutanyonenoticing', true],
            'Short url in string' => ['this link is facebook.com url', true],
            'Normal Text' => ['This link contains normal text 123', false]
        ];
    }

    /**
     * @dataProvider emailIsValidDataProvider
     */
    public function testEmailIsValid(string $emailString, bool $assertion)
    {
        // Mock scopeConfig call within emailIsValid
        $this->filterStatus->method('getBlockedAddresses')->willReturn(['*@qq.com', '*@*.ru', 'test@test.com']);

        $this->assertEquals($this->model->emailIsValid($emailString), $assertion);
    }

    public function emailIsValidDataProvider(): array
    {

        return [
            'Wildcard Prefix' => ['test@qq.com', false],
            'Wildcard Prefix & Domain' => ['test@yandex.ru', false],
            'Exact Match' => ['test@test.com', false],
            'No Match' => ['nomatch@gmail.com', true]
        ];
    }

    /**
     * @dataProvider stringMatchingAlphabetDataProvider
     */
    public function testIsStringMatchingBlockedAlphabet(string $string, bool $assertion)
    {

        $this->filterStatus->method('getBlockedAlphabets')->willReturn(['/\p{Cyrillic}+/u', '/\p{Han}+/u']);

        $this->assertEquals($this->model->isStringMatchingBlockedAlphabet($string), $assertion);

    }

    public function stringMatchingAlphabetDataProvider(): array
    {
        return [
            'Latin String' => ['Valid string with text', false],
            'String With Hanzi Characters' => ['String containing forbidden 汉字 characters', true]
        ];
    }

    /**
     * @dataProvider getAlphabetByStringDataProvider
     */
    public function testGetAlphabetByString(string $string, $assertion)
    {
        $this->filterStatus->method('getBlockedAlphabets')->willReturn(['/\p{Cyrillic}+/u', '/\p{Han}+/u']);

        $this->assertEquals($this->model->getAlphabetByString($string), $assertion);
    }

    public function getAlphabetByStringDataProvider(): array
    {
        return [
            'Latin String' => ['Valid string with text', null],
            'String With Hanzi Characters' => ['String containing forbidden 汉字 characters', 'Han']
        ];
    }
}
