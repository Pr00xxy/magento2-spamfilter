<?php
/**
 *
 * RulesProcessor.php
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
