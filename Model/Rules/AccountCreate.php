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

namespace PrOOxxy\SpamFilter\Model\Rules;

use Magento\Framework\Validator\ValidatorInterface;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\ValidatorBuilder;
use PrOOxxy\SpamFilter\Model\ValidatorBuilderFactory;
use Magento\Framework\ValidatorFactory;

class AccountCreate implements RulesInterface
{

    /**
     * @var SpamFilterStatus
     */
    private $filterStatus;

    /**
     * @var ValidatorBuilder
     */
    private $validatorBuilder;

    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

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
    public function addRules(): array
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
            $collection['firstname']->addValidator(
                $this->validatorBuilder->getNewNameValidator('firstname')
            );
            $collection['lastname']->addValidator(
                $this->validatorBuilder->getNewNameValidator('lastname')
            );
        }

        if ($this->filterStatus->isAlphabetBlockingEnabled()) {
            $collection['firstname']->addValidator(
                $this->validatorBuilder->getNewAlphabetValidator('firstname')
            );
            $collection['lastname']->addValidator(
                $this->validatorBuilder->getNewAlphabetValidator('lastname')
            );
        }

        if ($this->filterStatus->isEmailBlockingEnabled()) {
            $collection['email']->addValidator(
                $this->validatorBuilder->getNewEmailValidator('email')
            );
        }

        return $collection;

    }
}
