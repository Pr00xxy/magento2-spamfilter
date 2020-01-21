<?php
/**
 *
 * NewsletterBlocker.php
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


namespace PrOOxxy\SpamFilter\Model\Blocker;


use Magento\Framework\Exception\LocalizedException;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\Validator\DefaultValidator;

class NewsletterBlocker
{
    /**
     * @var DefaultValidator
     */
    private $validator;
    /**
     * @var SpamFilterStatus
     */
    private $filterStatus;

    public function __construct(
        DefaultValidator $validator,
        SpamFilterStatus $filterStatus
    )
    {
        $this->validator = $validator;
        $this->filterStatus = $filterStatus;
    }

    /**
     * @param string $email
     * @return bool
     * @throws LocalizedException
     */
    public function execute(string $email)
    {

        if (!$this->filterStatus->isScopeEnabled()) {
            return true;
        }

        $alphabetBlockingStatus = $this->filterStatus->isAlphabetBlockingEnabled();
        $emailBlockingStatus = $this->filterStatus->isEmailBlockingEnabled();

        if ($alphabetBlockingStatus && $this->validator->isStringMatchingBlockedAlphabet($email)) {
            $blockedByAlphabet = $this->validator->getAlphabetByString($email);
            throw new LocalizedException(__('%1 contains %2 characters which are forbidden', 'email', $blockedByAlphabet));
        }

        if ($emailBlockingStatus && !$this->validator->emailIsValid($email)) {
            throw new LocalizedException(__('Email %1 is blocked due to spam', $email));
        }
    }
}
