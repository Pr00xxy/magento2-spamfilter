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

namespace PrOOxxy\SpamFilter\Model\Rules;

use Magento\Framework\Validator\ValidatorInterface;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\ValidatorBuilder;
use PrOOxxy\SpamFilter\Model\ValidatorBuilderFactory;
use Magento\Framework\ValidatorFactory;

class AccountCreate implements RulesInterface
{

    private SpamFilterStatus $filterStatus;

    private ValidatorBuilder $validatorBuilder;

    private ValidatorFactory $validatorFactory;

    public function __construct(
        SpamFilterStatus $filterStatus,
        ValidatorBuilder $validatorBuilder,
        ValidatorFactory $validatorFactory
    ) {
        $this->filterStatus = $filterStatus;
        $this->validatorBuilder = $validatorBuilder;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @return ValidatorInterface[]
     */
    public function getRules(): array
    {

        $collection = [
            'firstname' => $this->validatorFactory->create(),
            'lastname' => $this->validatorFactory->create(),
            'email' => $this->validatorFactory->create()
        ];

        if (!$this->filterStatus->isScopeEnabled()) {
            return [];
        }

        if ($this->filterStatus->isLinkBlockingEnabled()) {
            $this->addNameValidator($collection);
        }

        if ($this->filterStatus->isAlphabetBlockingEnabled()) {
            $this->addAlphabetValidator($collection);
        }

        if ($this->filterStatus->isEmailBlockingEnabled()) {
            $this->addEmailValidator($collection);
        }

        return $collection;

    }

    private function addNameValidator(array &$collection)
    {
        $collection['firstname']->addValidator($this->validatorBuilder->getNewNameValidator('firstname'));
        $collection['lastname']->addValidator($this->validatorBuilder->getNewNameValidator('lastname'));
    }

    private function addAlphabetValidator(array &$collection)
    {
        $collection['firstname']->addValidator($this->validatorBuilder->getNewAlphabetValidator('firstname'));
        $collection['lastname']->addValidator($this->validatorBuilder->getNewAlphabetValidator('lastname'));
    }

    private function addEmailValidator(array &$collection)
    {
        $collection['email']->addValidator($this->validatorBuilder->getNewEmailValidator('email'));
    }
}
