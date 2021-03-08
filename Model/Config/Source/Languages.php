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

namespace PrOOxxy\SpamFilter\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Languages implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'label' => 'Arabic',
                'value' => '/\p{Arabic}+/u',
            ],
            [
                'label' => 'Armenian',
                'value' => '/\p{Armenian}+/u',
            ],
            [
                'label' => 'Bengali',
                'value' => '/\p{Bengali}+/u',
            ],
            [
                'label' => 'Bopomofo',
                'value' => '/\p{Bopomofo}+/u',
            ],
            [
                'label' => 'Buhid',
                'value' => '/\p{Buhid}+/u',
            ],
            [
                'label' => 'Cyrillic',
                'value' => '/\p{Cyrillic}+/u',
            ],
            [
                'label' => 'Ethiopic',
                'value' => '/\p{Ethiopic}+/u',
            ],
            [
                'label' => 'Georgian',
                'value' => '/\p{Georgian}+/u',
            ],
            [
                'label' => 'Greek',
                'value' => '/\p{Greek}+/u',
            ],
            [
                'label' => 'Gujarati',
                'value' => '/\p{Gujarati}+/u',
            ],
            [
                'label' => 'Gurmukhi',
                'value' => '/\p{Gurmukhi}+/u',
            ],
            [
                'label' => 'Han',
                'value' => '/\p{Han}+/u'
            ],
            [
                'label' => 'Hangul',
                'value' => '/\p{Hangul}+/u',
            ],
            [
                'label' => 'Hebrew',
                'value' => '/\p{Hebrew}+/u',
            ],
            [
                'label' => 'Kannada',
                'value' => '/\p{Kannada}+/u',
            ],
            [
                'label' => 'Khmer',
                'value' => '/\p{Khmer}+/u',
            ],
            [
                'label' => 'Lao',
                'value' => '/\p{Lao}+/u',
            ],
            [
                'label' => 'Malayalam',
                'value' => '/\p{Malayalam}+/u',
            ],
            [
                'label' => 'Mongolian',
                'value' => '/\p{Mongolian}+/u',
            ],
            [
                'label' => 'Myanmar',
                'value' => '/\p{Myanmar}+/u',
            ],
            [
                'label' => 'Oriya',
                'value' => '/\p{Oriya}+/u',
            ],
            [
                'label' => 'Sinhala',
                'value' => '/\p{Sinhala}+/u',
            ],
            [
                'label' => 'Syriac',
                'value' => '/\p{Syriac}+/u',
            ],
            [
                'label' => 'Tagbanwa',
                'value' => '/\p{Tagbanwa}+/u',
            ],
            [
                'label' => 'Tamil',
                'value' => '/\p{Tamil}+/u',
            ],
            [
                'label' => 'Telugu',
                'value' => '/\p{Telugu}+/u',
            ],
            [
                'label' => 'Thaana',
                'value' => '/\p{Thaana}+/u',
            ],
            [
                'label' => 'Thai',
                'value' => '/\p{Thai}+/u',
            ],
            [
                'label' => 'Tibetan',
                'value' => '/\p{Tibetan}+/u',
            ],
            [
                'label' => 'Yi',
                'value' => '/\p{Yi}+/u',
            ]
        ];
    }
}
