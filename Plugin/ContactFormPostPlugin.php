<?php
/**
 *
 * ContactFormPostPlugin.php
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

namespace PrOOxxy\SpamFilter\Plugin;

use Magento\Framework\Exception\LocalizedException;
use PrOOxxy\SpamFilter\Model\Blocker\ContactFormBlocker;

class ContactFormPostPlugin
{

    /**
     * @var ContactFormBlocker
     */
    private $blocker;

    public function __construct(
        ContactFormBlocker $blocker
    ) {
        $this->blocker = $blocker;
    }

    public function beforeExecute(
        \Magento\Contact\Controller\Index\Post $subject
    ) {

        $request = $subject->getRequest();
        if (trim($request->getParam('name')) === '') {
            throw new LocalizedException(__('Enter the Name and try again.'));
        }
        if (trim($request->getParam('comment')) === '') {
            throw new LocalizedException(__('Enter the comment and try again.'));
        }
        if (false === \strpos($request->getParam('email'), '@')) {
            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));
        }

        $dataPosts = [
            'name'      => $request->getParam('name'),
            'comment'   => $request->getParam('comment'),
            'email'     => $request->getParam('email')
        ];

        $this->blocker->execute($dataPosts);

        return null;
    }
}
