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

declare(strict_types=1);

namespace PrOOxxy\SpamFilter\Model;

use Magento\Framework\Validator\ValidatorInterface;
use PrOOxxy\SpamFilter\Model\Validator\AlphabetValidator;
use PrOOxxy\SpamFilter\Model\Validator\EmailValidator;
use PrOOxxy\SpamFilter\Model\Validator\NameValidator;
use PrOOxxy\SpamFilter\Model\Validator\ValidatorFactory;

class ValidatorBuilder
{

    /**
     * @var SpamFilterStatus
     */
    private $status;

    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    public function __construct(
        ValidatorFactory $validatorFactory,
        SpamFilterStatus $status
    ) {
        $this->validatorFactory = $validatorFactory;
        $this->status = $status;
    }

    public function getNewNameValidator(string $field): ValidatorInterface
    {
        if (!\in_array($field, ['firstname', 'lastname', 'name'])) {
            throw new \InvalidArgumentException('field must be either firstname or lastname');
        }

        return $this->getValidator(NameValidator::class, ['field' => $field]);
    }

    public function getNewAlphabetValidator(string $field): ValidatorInterface
    {
        return $this->getValidator(
            AlphabetValidator::class,
            [
                'field' => $field,
                'config' => $this->status
            ]
        );
    }

    public function getNewEmailValidator(string $field): ValidatorInterface
    {
        if ($field !== 'email') {
            throw new \InvalidArgumentException('field must be email');
        }

        return $this->getValidator(
            EmailValidator::class,
            [
                'field' => $field,
                'config' => $this->status
            ]
        );
    }

    private function getValidator($class, array $dependencies): ValidatorInterface
    {
        return $this->validatorFactory->create($class, $dependencies);
    }
}
