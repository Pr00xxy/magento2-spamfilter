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

declare(strict_types=1);

namespace PrOOxxy\SpamFilter\Model;

use Magento\Framework\Validator\ValidatorInterface;

class RulesProcessor
{
    public function process(array $collection, array $dataPosts): array
    {
        $messages = [];
        foreach ($collection as $key => $validator) {
            /** @var $validator ValidatorInterface */
            if (!$validator instanceof ValidatorInterface) {
                continue;
            }
            if (\array_key_exists($key, $dataPosts) && !$validator->isValid($dataPosts[$key])) {
                $messages[] = $validator->getMessages();
            }
        }

        return count($messages) === 0 ? [] : array_values(array_merge(...$messages));
    }
}
