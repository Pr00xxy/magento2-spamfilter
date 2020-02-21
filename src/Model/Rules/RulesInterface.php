<?php
/**
 * Lybe
 *
 * NOTICE OF LICENSE
 * This source file is subject to the Lybe.com license that is
 * available through the world-wide-web at this URL:
 * http://www.lybe.se/licence-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Lybe
 * @package     Lybe_{ModuleName}
 * @copyright   Copyright (c) 2020 Lybe Sweden AB (https://www.lybe.se/)
 *
 * @author      Hampus Westman <hampus.westman@lybe.se>
 *
 */

namespace PrOOxxy\SpamFilter\Model\Rules;

use Zend\Validator\ValidatorInterface;

interface RulesInterface
{
    /**
     * @return ValidatorInterface[]
     */
    public function addRules(): array;
}
