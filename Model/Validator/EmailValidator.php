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

namespace PrOOxxy\SpamFilter\Model\Validator;

use Magento\Framework\Validator\ValidatorInterface;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;

class EmailValidator implements ValidatorInterface
{

    private const INVALID = 'regexInvalid';

    protected $messageTemplates = [
        self::INVALID   => "%s is blocked to prevent spam"
    ];

    private $messages = [];

    /**
     * @var SpamFilterStatus
     */
    private $config;

    /**
     * @var string
     */
    protected $field;

    public function __construct(
        SpamFilterStatus $config,
        string $field
    ) {
        $this->config = $config;
        $this->field = $field;
    }

    public function isValid($email): bool
    {
        if ($this->isDomainBlocked($email)) {
            $this->addMessage(self::INVALID);
            return false;
        }

        return true;
    }

    private function addMessage(string $messageKey): void
    {
        $this->messages[$messageKey] = sprintf(__($this->messageTemplates[$messageKey]), $this->field);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    private function isDomainBlocked(string $email): bool
    {

        $domains = $this->config->getBlockedAddresses();

        if (empty($domains)) {
            return true;
        }

        foreach ($domains as $filter) {

            if (empty(\trim($filter))) {
                continue;
            }

            if (\strpos($filter, '@') === false) {
                continue;
            }

            $expression = '/' . \trim(\str_replace('*', '(.*?)', \str_replace('.', '\.', $filter))) . '/';
            if (\preg_match($expression, $email)) {
                return true;
            }
        }

        return false;
    }

    public function setTranslator($translator = null)
    {
    }

    public function hasTranslator()
    {
        return false;
    }

    public function getTranslator()
    {
        return null;
    }

}
