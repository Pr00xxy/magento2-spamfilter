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

namespace PrOOxxy\SpamFilter\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SpamFilterStatus
{

    private const MODULE_STATUS_CONFIG_PATH = 'spamfilter/general/module_status';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var string
     */
    private $scope;

    public function __construct(
        ScopeConfigInterface $config,
        $scope = ''
    ) {
        $this->config = $config;
        $this->scope = $scope;
    }

    public function isSpamFilterEnabled(): bool
    {
        return (bool) $this->config->getValue(self::MODULE_STATUS_CONFIG_PATH);
    }

    public function isScopeEnabled(): bool
    {
        return $this->isSpamFilterEnabled() ? (bool) $this->config->getValue("spamfilter/{$this->scope}/scope_enable") : false;
    }

    public function isLinkBlockingEnabled(): bool
    {
        return (bool) $this->config->getValue("spamfilter/{$this->scope}/link_blocking_enable");
    }

    public function isEmailBlockingEnabled(): bool
    {
        return (bool) $this->config->getValue("spamfilter/{$this->scope}/email_blocking_enable");
    }

    public function isAlphabetBlockingEnabled(): bool
    {
        return (bool) $this->config->getValue("spamfilter/{$this->scope}/alphabet_blocking_enable");
    }

    public function getBlockedAddresses(): array
    {
        return (array) \preg_split(
            '/,|\n/',
            $this->config->getValue('spamfilter/general/email_blocked_addresses')
        );
    }

    public function getBlockedAlphabets(): array
    {
        $blockedAlphabets = (string) $this->config->getValue("spamfilter/{$this->scope}/alphabet_blocked_alphabets");
        if (empty($blockedAlphabets)) {
            return [];
        }
        return (array) \explode(',', $blockedAlphabets);
    }
}
