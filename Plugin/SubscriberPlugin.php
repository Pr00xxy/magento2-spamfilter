<?php

declare(strict_types=1);

namespace PrOOxxy\SpamFilter\Plugin;

use PrOOxxy\SpamFilter\Model\Blocker\NewsletterBlocker;

class SubscriberPlugin
{

    /**
     * @var NewsletterBlocker
     */
    private $blocker;

    public function __construct(
        NewsletterBlocker $blocker
    ) {
        $this->blocker = $blocker;
    }

    public function beforeSubscribe(\Magento\Newsletter\Model\Subscriber $subject, $email)
    {

        $this->blocker->execute($email);

    }
}
