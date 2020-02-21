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

namespace PrOOxxy\SpamFilter\Model\Rules;

use Magento\Framework\ValidatorFactory;
use PrOOxxy\SpamFilter\Model\SpamFilterStatus;
use PrOOxxy\SpamFilter\Model\Validator\DefaultValidator;
use PrOOxxy\SpamFilter\Model\ValidatorBuilder;

class Newsletter implements RulesInterface
{

    /**
     * @var SpamFilterStatus
     */
    private $filterStatus;

    /**
     * @var ValidatorBuilder
     */
    private $validatorBuilder;

    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    public function __construct(
        SpamFilterStatus $filterStatus,
        ValidatorBuilder $validatorBuilder,
        ValidatorFactory $validatorFactory
    ) {
        $this->filterStatus = $filterStatus;
        $this->validatorBuilder = $validatorBuilder;
        $this->validatorFactory = $validatorFactory;
    }

    public function addRules(): array
    {

        if (!$this->filterStatus->isScopeEnabled()) {
            return [];
        }

        $collection = [
            'email' => $this->validatorFactory->create()
        ];

        if ($this->filterStatus->isEmailBlockingEnabled()) {
            $collection['email']->addValidator(
                $this->validatorBuilder->getNewEmailValidator('email')
            );
        }

        return $collection;

    }
}
