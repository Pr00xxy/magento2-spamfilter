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
