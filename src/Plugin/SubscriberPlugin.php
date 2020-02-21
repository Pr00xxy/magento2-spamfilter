<?php

declare(strict_types=1);

namespace PrOOxxy\SpamFilter\Plugin;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use PrOOxxy\SpamFilter\Model\Rules\Newsletter;
use PrOOxxy\SpamFilter\Model\RulesProcessor;

class SubscriberPlugin
{

    public function __construct(
        Newsletter $newsletterRules,
        ManagerInterface $manager,
        RulesProcessor $rulesProcessor
    )  {
        $this->newsletterRules = $newsletterRules;
        $this->manager = $manager;
        $this->rulesProcessor = $rulesProcessor;
    }

    public function beforeSubscribe(\Magento\Newsletter\Model\Subscriber $subject, $email)
    {
        $dataPosts = [
            'email' => $email
        ];

        $collection = $this->newsletterRules->addRules();

        $messages = $this->rulesProcessor->process($collection, $dataPosts);

        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->manager->addErrorMessage($message);
            }
            throw new LocalizedException(__('SpamFilter: Could not save customer'));
        }
    }
}
