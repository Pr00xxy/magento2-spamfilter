<?php
/**
 *
 * AlphabetValidator.php
 *
 * This file is part of Foobar.
 *
 * {module_name} is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * {module_name} is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with {module_name}.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category   Pr00xxy
 * @package    {package_name}
 * @author     Hampus Westman <hampus.westman@gmail.com>
 * @copyright  Copyright (c) 2020 Hampus Westman
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GPLv3.0
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
