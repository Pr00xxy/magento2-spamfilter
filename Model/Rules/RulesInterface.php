<?php
/**
 * Copyright © Hampus Westman 2021
 * See LICENCE provided with this module for licence details
 *
 * @author     Hampus Westman <hampus.westman@gmail.com>
 * @copyright  Copyright (c) 2021 Hampus Westman
 * @license    MIT License https://opensource.org/licenses/MIT
 * @link       https://github.com/Pr00xxy
 *
 */

namespace PrOOxxy\SpamFilter\Model\Rules;

use Magento\Framework\Validator\ValidatorInterface;

interface RulesInterface
{
    /**
     * @return ValidatorInterface[]
     */
    public function getRules(): array;
}
