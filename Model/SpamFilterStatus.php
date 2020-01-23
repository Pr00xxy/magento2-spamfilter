<?php
/**
 *
 * SpamFilterStatus.php
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


namespace PrOOxxy\SpamFilter\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SpamFilterStatus
{

    /** @var ScopeConfigInterface */
    private $config;

    private const MODULE_STATUS_CONFIG_PATH = 'spamfilter/general/module_status';
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
        return $this->isSpamFilterEnabled() ? $this->config->getValue("spamfilter/{$this->scope}/scope_enable") : false;
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
        return (array) preg_split(
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
        return (array) explode(',', $blockedAlphabets);
    }
}
