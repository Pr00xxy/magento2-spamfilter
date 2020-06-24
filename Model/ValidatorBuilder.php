<?php
/**
 * Copyright © Hampus Westman 2020
 * See LICENCE provided with this module for licence details
 *
 * @author     Hampus Westman <hampus.westman@gmail.com>
 * @copyright  Copyright (c) 2020 Hampus Westman
 * @license    MIT License https://opensource.org/licenses/MIT
 * @link       https://github.com/Pr00xxy
 *
 */
declare(strict_types=1);


namespace PrOOxxy\SpamFilter\Model;

use InvalidArgumentException;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidatorFactory;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidatorFactory;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidatorFactory;

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
