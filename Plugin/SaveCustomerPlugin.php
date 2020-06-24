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

namespace PrOOxxy\SpamFilter\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use PrOOxxy\SpamFilter\Model\Rules\AccountCreate;
use PrOOxxy\SpamFilter\Model\RulesProcessor;

class SaveCustomerPlugin
{

    /**
     * @var AccountCreate
     */
    private $accountCreateRules;
    /**
     * @var ManagerInterface
     */
    private $manager;
    /**
     * @var RulesProcessor
     */
    private $rulesProcessor;

    public function __construct(
        AccountCreate $accountCreateRules,
        ManagerInterface $manager,
        RulesProcessor $rulesProcessor
    )  {
        $this->accountCreateRules = $accountCreateRules;
        $this->manager = $manager;
        $this->rulesProcessor = $rulesProcessor;
    }

    /**
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     * @see \Magento\Customer\Api\CustomerRepositoryInterface::save
     */
    public function beforeSave(
        \Magento\Customer\Api\CustomerRepositoryInterface $subject,
        \Magento\Customer\Api\Data\CustomerInterface $customer,
        $passwordHash = null
    ) {

        $dataPosts = [
            'firstname' => $customer->getFirstname(),
            'lastname' => $customer->getLastname(),
            'email' => $customer->getEmail()
        ];

        $collection = $this->accountCreateRules->addRules();

        $messages = $this->rulesProcessor->process($collection, $dataPosts);

        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->manager->addErrorMessage($message);
            }
            throw new LocalizedException(__('SpamFilter: Could not save customer'));
        }


    }

}