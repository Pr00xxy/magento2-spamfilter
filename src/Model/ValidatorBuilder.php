<?php
/**
 *
 * ValidatorBuilder.php
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
declare(strict_types=1);


namespace PrOOxxy\SpamFilter\Model;

use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidatorFactory;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidatorFactory;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidatorFactory;
use Psr\Log\InvalidArgumentException;

class ValidatorBuilder
{

    /**
     * @var NameValidatorFactory
     */
    private $nameValidatorFactory;
    /**
     * @var SpamFilterStatus
     */
    private $status;
    /**
     * @var AlphabetValidatorFactory
     */
    private $alphabetValidatorFactory;
    /**
     * @var EmailValidatorFactory
     */
    private $emailValidatorFactory;

    public function __construct(
        NameValidatorFactory $nameValidatorFactory,
        AlphabetValidatorFactory $alphabetValidatorFactory,
        EmailValidatorFactory $emailValidatorFactory,
        SpamFilterStatus $status
    ) {
        $this->nameValidatorFactory = $nameValidatorFactory;
        $this->status = $status;
        $this->alphabetValidatorFactory = $alphabetValidatorFactory;
        $this->emailValidatorFactory = $emailValidatorFactory;
    }

    public function getNewNameValidator(string $field): ?NameValidator
    {
        if (!\in_array($field, ['firstname', 'lastname'])) {
            throw new \InvalidArgumentException('field must be either firstname or lastname');
        }

        return $this->nameValidatorFactory->create(
            [
                'field' => $field,
            ]
        );

    }

    public function getNewAlphabetValidator(string $field): AlphabetValidator
    {
        return $this->alphabetValidatorFactory->create(
            [
                'field' => $field,
                'config' => $this->status
            ]
        );
    }

    public function getNewEmailValidator(string $field): EmailValidator
    {

        if ($field !== 'email') {
            throw new InvalidArgumentException('field must be email');
        }

        return $this->emailValidatorFactory->create(
            [
                'field' => $field,
                'config' => $this->status
            ]
        );
    }
}
