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

namespace PrOOxxy\SpamFilter\Model\Validator;

use Magento\Framework\Validator\ValidatorInterface;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;

class AlphabetValidator implements ValidatorInterface
{

    private const INVALID_CHARACTER_SET = 'CHARSET_INVALID';
    private const INVALID_UNDEFINED_CHARSET = 'CHARSET_UNKNOWN_INVALID';

    protected $messageTemplates = [
        self::INVALID_CHARACTER_SET => "The %s does not allow the %s character set",
        self::INVALID_UNDEFINED_CHARSET => "The %s detected illegal characters"
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

    public function isValid($value): bool
    {
        if (!$this->isFieldMatchingBlockedAlphabet($value)) {
            return true;
        }

        $alphabet = $this->getAlphabetByString($value);

        if ($alphabet === null) {
            $this->addMessage(self::INVALID_UNDEFINED_CHARSET);
        } else {
            $this->addMessage(self::INVALID_CHARACTER_SET, [$this->field, $alphabet]);
        }

        return false;

    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    private function addMessage(string $messageKey, array $fields = [])
    {
        if (empty($fields)) {
            $fields = [$this->field];
        }
        $this->messages[$messageKey] = vsprintf($this->messageTemplates[$messageKey], $fields);
    }

    private function isFieldMatchingBlockedAlphabet(string $field): bool
    {
        foreach ($this->config->getBlockedAlphabets() as $alphabet) {
            if (\preg_match($alphabet, $field)) {
                return true;
            }
        }
        return false;
    }

    private function getAlphabetByString(string $string)
    {

        foreach ($this->config->getBlockedAlphabets() as $alphabet) {
            if (\preg_match($alphabet, $string)) {
                \preg_match('/{(.*?)}/', $alphabet, $match);
                return $match[1];
            }
        }

        return null;
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
