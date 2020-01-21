<?php
/**
 *
 * AccountCreateBlockerTest.php
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

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Blocker;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PrOOxxy\SpamFilter\Model\Validator\DefaultValidator;

class ContactFormBlockerTest extends TestCase
{

    /**
     * @var $objectManager
     */
    private $objectManager;

    /**
     * @var $model \PrOOxxy\SpamFilter\Model\Blocker\AccountCreateBlocker
     */
    private $model;

    private $validator;

    private $filterStatus;

    public function setup()
    {
        parent::setUp();

        $this->objectManager = new ObjectManager($this);

        $this->validator = $this->getMockBuilder(DefaultValidator::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getAlphabetByString',
                    'stringHasLink',
                    'emailIsValid',
                    'isStringMatchingBlockedAlphabet'
                ]
            )->getMock();

        $this->filterStatus = $this->getMockBuilder(\PrOOxxy\SpamFilter\Model\SpamFilterStatus::class)
            ->setMethods([
                'isScopeEnabled',
                'isLinkBlockingEnabled',
                'isAlphabetBlockingEnabled',
                'isEmailBlockingEnabled',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterStatus->method('isScopeEnabled')->willReturn(true);
        $this->filterStatus->method('isLinkBlockingEnabled')->willReturn(true);
        $this->filterStatus->method('isAlphabetBlockingEnabled')->willReturn(true);
        $this->filterStatus->method('isEmailBlockingEnabled')->willReturn(true);


        $this->model = $this->objectManager->getObject(
            \PrOOxxy\SpamFilter\Model\Blocker\ContactFormBlocker::class,
            [
                'filterStatus' => $this->filterStatus,
                'validator' => $this->validator
            ]
        );
    }

    public function testExecuteWithValidData()
    {
        $dataPosts = [
            'name' => 'Bob',
            'comment' => 'random comment string',
            'email' => 'foo@bar.com'
        ];

        $this->validator->method('emailIsValid')
            ->willReturn(true);

        $this->assertNotFalse($this->model->execute($dataPosts));

    }

    public function testExecuteWithLinkInFirstname()
    {

        $dataPosts = ['name' => 'string with link'];

        $this->validator->method('stringHasLink')->willReturn(true);

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('name contains web link');
        $this->model->execute($dataPosts);

    }

    public function testExecuteWithInvalidCharactersInComment()
    {
        $dataPosts = ['comment' => 'ﺏﺕﺙﺝ'];

        $this->validator->method('isStringMatchingBlockedAlphabet')->with($dataPosts['comment'])->willReturn(true);

        $this->validator->method('getAlphabetByString')->with($dataPosts['comment'])->willReturn('Arabic');

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('comment contains Arabic characters which are forbidden');
        $this->model->execute($dataPosts);

    }

    public function testExecuteWithBlockedDomain()
    {
        $dataPosts = [
            'name' => 'some letters',
            'email' => 'forbidden@email.se'
        ];

        $this->validator->method('emailIsValid')->with($dataPosts['email'])->willReturn(false);

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Email forbidden@email.se is blocked due to spam');
        $this->model->execute($dataPosts);
    }

}
