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

class NewsletterBlockerTests extends TestCase
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
                    'emailIsValid',
                    'isStringMatchingBlockedAlphabet'
                ]
            )->getMock();

        $this->filterStatus = $this->getMockBuilder(\PrOOxxy\SpamFilter\Model\SpamFilterStatus::class)
            ->setMethods([
                'isScopeEnabled',
                'isAlphabetBlockingEnabled',
                'isEmailBlockingEnabled',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterStatus->method('isScopeEnabled')->willReturn(true);
        $this->filterStatus->method('isAlphabetBlockingEnabled')->willReturn(true);
        $this->filterStatus->method('isEmailBlockingEnabled')->willReturn(true);


        $this->model = $this->objectManager->getObject(
            \PrOOxxy\SpamFilter\Model\Blocker\NewsletterBlocker::class,
            [
                'filterStatus' => $this->filterStatus,
                'validator' => $this->validator
            ]
        );
    }

    public function testExecuteWithValidData()
    {
        $email = 'foo@bar.se';

        $this->validator->method('emailIsValid')->willReturn(true);

        $this->assertNotFalse($this->model->execute($email));

    }

    public function testExecuteWithInvalidCharacters()
    {
        $email = 'ﺏﺕﺙﺝ' . '@email.se';

        $this->validator->method('isStringMatchingBlockedAlphabet')->with($email)->willReturn(true);

        $this->validator->method('getAlphabetByString')->with($email)->willReturn('Arabic');

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('email contains Arabic characters which are forbidden');
        $this->model->execute($email);

    }

    public function testExecuteWithBlockedDomain()
    {
        $email = 'forbidden@email.se';

        $this->validator->method('emailIsValid')->with($email)->willReturn(false);

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Email forbidden@email.se is blocked due to spam');
        $this->model->execute($email);
    }

}
