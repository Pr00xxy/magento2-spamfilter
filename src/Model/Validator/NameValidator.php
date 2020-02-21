<?php
/**
 *
 * NameValidator.php
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
