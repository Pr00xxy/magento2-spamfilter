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

use Magento\Contact\Controller\Index\Post;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use PrOOxxy\SpamFilter\Model\Rules\ContactForm;
use PrOOxxy\SpamFilter\Model\RulesProcessor;
use Magento\Framework\Controller\Result\RedirectFactory;

class ContactFormPostPlugin
{

    private ContactForm $contactFormRules;

    private ManagerInterface $manager;

    private RulesProcessor $rulesProcessor;

    private RedirectFactory $redirectFactory;

    public function __construct(
        ContactForm $contactFormRules,
        ManagerInterface $manager,
        RulesProcessor $rulesProcessor,
        RedirectFactory $redirectFactory
    ) {
        $this->contactFormRules = $contactFormRules;
        $this->manager = $manager;
        $this->rulesProcessor = $rulesProcessor;
        $this->redirectFactory = $redirectFactory;
    }

    public function beforeExecute(
        Post $subject
    ) {

        $request = $subject->getRequest();

        // A copy of the validation process that the original function executes.
        if (\trim($request->getParam('name')) === '') {
            throw new LocalizedException(__('Enter the Name and try again.'));
        }
        if (\trim($request->getParam('comment')) === '') {
            throw new LocalizedException(__('Enter the comment and try again.'));
        }
        if (false === \strpos($request->getParam('email'), '@')) {
            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));
        }

        $dataPosts = [
            'name'      => $request->getParam('name'),
            'comment'   => $request->getParam('comment'),
            'email'     => $request->getParam('email')
        ];

        $collection = $this->contactFormRules->getRules();

        $messages = $this->rulesProcessor->process($collection, $dataPosts);

        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->manager->addErrorMessage($message);
            }
            return $this->redirectFactory->create()->setPath('contact/index');
        }

        return null;
    }
}
