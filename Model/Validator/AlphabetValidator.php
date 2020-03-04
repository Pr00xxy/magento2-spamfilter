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

use PrOOxxy\SpamFilter\Model\SpamFilterStatus;

class AlphabetValidator extends \Zend_Validate_Abstract implements \Magento\Framework\Validator\ValidatorInterface
{

    private const INVALID_CHARACTER_SET = 'CHARSET_INVALID';
    private const INVALID_UNDEFINED_CHARSET = 'CHARSET_UNKNOWN_INVALID';

    protected $_messageTemplates = [
        self::INVALID_CHARACTER_SET => "The '%value%' does not allow the '%charset%' character set",
        self::INVALID_UNDEFINED_CHARSET => "The '%value%' detected illegal characters"
    ];

    protected $_messageVariables = [
        'charset' => 'charset',
        'field' => 'field'
    ];

    /**
     * @var SpamFilterStatus
     */
    private $config;

    /**
     * @var string
     */
    protected $field;

    /** @var string */
    protected $charset = 'unknown';

    public function __construct(
        SpamFilterStatus $config,
        string $field
    ) {
        $this->config = $config;
        $this->field = $field;
    }

    public function isValid($value)
    {
        if (!$this->isFieldMatchingBlockedAlphabet($value)) {
            return true;
        }

        $alphabet = $this->getAlphabetByString($value);
        if ($alphabet === null) {
            $this->_error(self::INVALID_UNDEFINED_CHARSET, $this->field);
        } else {
            $this->charset = $alphabet;
            $this->_error(self::INVALID_CHARACTER_SET, $this->field);
        }

        return false;

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

}
