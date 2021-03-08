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

class NameValidator implements ValidatorInterface
{

    /**
     * @var string
     */
    private $pattern = '/(?:(?:https?|ftp):\/\/|\b(?:[a-z\d]+\.))(?:(?:[^\s()<>]+|\((?:[^\s()<>]+|(?:\([^\s()<>]+\)))?\))+(?:\((?:[^\s()<>]+|(?:\(?:[^\s()<>]+\)))?\)|))?/m';

    private $messages = [];

    private const INVALID_NOT_STRING = 'nameInvalid';
    private const INVALID_LINK = 'nameInvalidLink';

    protected $messageTemplates = [
        self::INVALID_LINK => "Provided %1 has embedded web url",
        self::INVALID_NOT_STRING => "Provided %1 must be of type string"
    ];

    /**
     * @var string
     */
    protected $field;

    public function __construct(
        string $field
    ) {
        $this->field = $field;
    }

    public function isValid($value): bool
    {

        if (!\is_string($value)) {
            $this->addMessage(self::INVALID_NOT_STRING);
            return false;
        }

        if ($this->stringHasLink($value)) {
            $this->addMessage(self::INVALID_LINK);
            return false;
        }

        return true;
    }

    private function stringHasLink(string $value): bool
    {
        return (bool) \preg_match($this->pattern, $value);
    }

    private function addMessage(string $messageKey): void
    {
        $this->messages[$messageKey] = sprintf(__($this->messageTemplates[$messageKey]), $this->field);
    }

    public function getMessages(): array
    {
        return $this->messages;
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
