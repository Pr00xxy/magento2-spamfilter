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
use Magento\Store\Model\ScopeInterface;

class SpamFilterStatus
{

    private const MODULE_STATUS_CONFIG_PATH = 'spamfilter/general/module_status';

    protected const CONFIG_SCOPE = ScopeInterface::SCOPE_WEBSITES;

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
        string $scope
    ) {
        $this->config = $config;
        $this->scope = $scope;
    }

    public function isSpamFilterEnabled(): bool
    {
        return (bool) $this->config->getValue(self::MODULE_STATUS_CONFIG_PATH, self::CONFIG_SCOPE);
    }

    public function isScopeEnabled(): bool
    {
        return $this->isSpamFilterEnabled() ? (bool) $this->config->getValue("spamfilter/{$this->scope}/scope_enable", self::CONFIG_SCOPE) : false;
    }

    public function isLinkBlockingEnabled(): bool
    {
        return (bool) $this->config->getValue("spamfilter/{$this->scope}/link_blocking_enable", self::CONFIG_SCOPE);
    }

    public function isEmailBlockingEnabled(): bool
    {
        return (bool) $this->config->getValue("spamfilter/{$this->scope}/email_blocking_enable", self::CONFIG_SCOPE);
    }

    public function isAlphabetBlockingEnabled(): bool
    {
        return (bool) $this->config->getValue("spamfilter/{$this->scope}/alphabet_blocking_enable", self::CONFIG_SCOPE);
    }

    public function getBlockedAddresses(): array
    {
        return (array) \preg_split(
            '/,|\n/',
            $this->config->getValue('spamfilter/general/email_blocked_addresses', self::CONFIG_SCOPE)
        );
    }

    public function getBlockedAlphabets(): array
    {
        $blockedAlphabets = (string) $this->config->getValue("spamfilter/{$this->scope}/alphabet_blocked_alphabets", self::CONFIG_SCOPE);
        if (empty($blockedAlphabets)) {
            return [];
        }
        return (array) \explode(',', $blockedAlphabets);
    }
}
