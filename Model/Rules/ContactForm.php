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

use Magento\Framework\ValidatorFactory;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\Validator\DefaultValidator;
use PrOOxxy\SpamFilter\Model\ValidatorBuilder;

class ContactForm implements RulesInterface
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


    public function addRules(): array
    {

        if (!$this->filterStatus->isScopeEnabled()) {
            return [];
        }

        $collection = [
            'name' => $this->validatorFactory->create(),
            'comment' => $this->validatorFactory->create(),
            'email' => $this->validatorFactory->create()
        ];

        if ($this->filterStatus->isLinkBlockingEnabled()) {
            $collection['name']->addValidator(
                $this->validatorBuilder->getNewNameValidator('name')
            );
        }

        if ($this->filterStatus->isAlphabetBlockingEnabled()) {
            $collection['name']->addValidator(
                $this->validatorBuilder->getNewAlphabetValidator('name')
            );
            $collection['comment']->addValidator(
                $this->validatorBuilder->getNewAlphabetValidator('comment')
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
