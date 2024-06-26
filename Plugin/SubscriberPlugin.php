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

use Magento\Framework\Message\ManagerInterface;
use Magento\Newsletter\Model\Subscriber;
use PrOOxxy\SpamFilter\Exception\ValidatorException;
use PrOOxxy\SpamFilter\Model\Rules\Newsletter;
use PrOOxxy\SpamFilter\Model\RulesProcessor;

class SubscriberPlugin
{

    private RulesProcessor $rulesProcessor;

    private ManagerInterface $manager;

    private Newsletter $newsletterRules;

    public function __construct(
        Newsletter $newsletterRules,
        ManagerInterface $manager,
        RulesProcessor $rulesProcessor
    ) {
        $this->newsletterRules = $newsletterRules;
        $this->manager = $manager;
        $this->rulesProcessor = $rulesProcessor;
    }

    /**
     * @throws ValidatorException
     */
    public function beforeSubscribe(Subscriber $subject, $email)
    {
        $dataPosts = [
            'email' => $email
        ];

        $collection = $this->newsletterRules->getRules();

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
