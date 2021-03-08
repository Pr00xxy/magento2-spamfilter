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

namespace PrOOxxy\SpamFilter\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Message\ManagerInterface;
use PrOOxxy\SpamFilter\Exception\ValidatorException;
use PrOOxxy\SpamFilter\Model\Rules\AccountCreate;
use PrOOxxy\SpamFilter\Model\RulesProcessor;

class SaveCustomerPlugin
{

    private AccountCreate $accountCreateRules;

    private ManagerInterface $manager;

    private RulesProcessor $rulesProcessor;

    public function __construct(
        AccountCreate $accountCreateRules,
        ManagerInterface $manager,
        RulesProcessor $rulesProcessor
    ) {
        $this->accountCreateRules = $accountCreateRules;
        $this->manager = $manager;
        $this->rulesProcessor = $rulesProcessor;
    }

    /**
     * @throws ValidatorException()
     * @see \Magento\Customer\Api\CustomerRepositoryInterface::save
     */
    public function beforeSave(
        CustomerRepositoryInterface $subject,
        CustomerInterface $customer,
        $passwordHash = null
    ) {

        $dataPosts = [
            'firstname' => $customer->getFirstname(),
            'lastname' => $customer->getLastname(),
            'email' => $customer->getEmail()
        ];

        $collection = $this->accountCreateRules->getRules();

        $messages = $this->rulesProcessor->process($collection, $dataPosts);

        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->manager->addErrorMessage($message);
            }
            throw new ValidatorException(__('Customer does not pass spam filter validation'));
        }

        return null;

    }

}
