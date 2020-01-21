<?php

namespace PrOOxxy\SpamFilter\Plugin;

use PrOOxxy\SpamFilter\Model\Blocker\AccountCreateBlocker;

class SaveCustomerPlugin
{

    /**
     * @var AccountCreateBlocker
     */
    private $blocker;

    public function __construct(
        AccountCreateBlocker $blocker
    ) {
        $this->blocker = $blocker;
    }

    /*
     * @throws LocalizedException
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

        $this->blocker->execute($dataPosts);

    }

}
