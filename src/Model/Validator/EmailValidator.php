<?php
/**
 *
 * EmailValidator.php
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
declare(strict_types=1);


namespace PrOOxxy\SpamFilter\Model\Validator;


use PrOOxxy\SpamFilter\Model\SpamFilterStatus;

class EmailValidator extends \Zend_Validate_Abstract implements \Magento\Framework\Validator\ValidatorInterface
{

    private const INVALID = 'regexInvalid';

    protected $_messageTemplates = [
        self::INVALID   => "'%field%' is blocked by the spam filter"
    ];

    protected $_messageVariables = [
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
            $this->_error(self::INVALID);
            return false;
        }

        return true;
    }

    public function isDomainBlocked(string $email): bool
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
}
