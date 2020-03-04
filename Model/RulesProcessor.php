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

use Magento\Framework\Validator;

class RulesProcessor
{
    public function process(array $collection, array $dataPosts): array
    {
        $messages = [];
        foreach ($collection as $key => $validator) {
            /** @var $validator Validator */
            if (!$validator instanceof Validator) {
                continue;
            }
            if (\array_key_exists($key, $dataPosts)) {
                if (!$validator->isValid($dataPosts[$key])) {
                    $messages[] = \array_merge($messages, \array_values($validator->getMessages()));
                }
            }
        }

        return $messages;
    }
}
