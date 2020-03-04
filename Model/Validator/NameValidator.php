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

class NameValidator extends \Zend_Validate_Abstract implements \Magento\Framework\Validator\ValidatorInterface
{

    /**
     * @var string
     */
    private $pattern = '/(?:(?:https?|ftp):\/\/|\b(?:[a-z\d]+\.))(?:(?:[^\s()<>]+|\((?:[^\s()<>]+|(?:\([^\s()<>]+\)))?\))+(?:\((?:[^\s()<>]+|(?:\(?:[^\s()<>]+\)))?\)|))?/m';

    private const INVALID_NOT_STRING = 'nameInvalid';
    private const INVALID_LINK = 'nameInvalidLink';

    protected $_messageTemplates = [
        self::INVALID_LINK => "Provided '%field%' has embedded web url",
        self::INVALID_NOT_STRING => "Provided '%field%' must be of type string"
    ];

    /**
     * @var string
     */
    protected $field;

    public function __construct(
        string $field
    )
    {
        $this->field = $field;
    }

    public function isValid($value): bool
    {

        if (!\is_string($value)) {
            $this->_error(self::INVALID_NOT_STRING);
            return false;
        }

        if ($this->stringHasLink($value)) {
            $this->_error(self::INVALID_LINK);
            return false;
        }

        return true;
    }

    public function stringHasLink(string $value): bool
    {
        return (bool) \preg_match($this->pattern, $value);
    }

}
