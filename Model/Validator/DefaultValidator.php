<?php

declare(strict_types=1);

namespace PrOOxxy\SpamFilter\Model\Validator;

use PrOOxxy\SpamFilter\Model\SpamFilterStatus;

class DefaultValidator
{

    /**
     * @var SpamFilterStatus
     */
    private $status;
    /**
     * @var string
     */
    private $scope;

    public function __construct(
        SpamFilterStatus $status,
        $scope = ''
    ) {
        $this->status = $status;
        $this->scope = $scope;
    }

    public function stringHasLink(string $dataPost): bool
    {
        // @codingStandardsIgnoreStart
        $urlRegexPattern = '/(?:(?:https?|ftp):\/\/|\b(?:[a-z\d]+\.))(?:(?:[^\s()<>]+|\((?:[^\s()<>]+|(?:\([^\s()<>]+\)))?\))+(?:\((?:[^\s()<>]+|(?:\(?:[^\s()<>]+\)))?\)|))?/m';
        // @codingStandardsIgnoreEnd
        return (bool) preg_match($urlRegexPattern, $dataPost);
    }

    public function emailIsValid($email): bool
    {
        $domains = $this->status->getBlockedAddresses();

        if (!$domains || empty($domains)) {
            return true;
        }

        if (!is_array($domains)) {
            return true;
        }

        foreach ($domains as $key => $filter) {

            if (empty(trim($filter))) {
                continue;
            }

            if (strpos($filter, '@') === false) {
                continue;
            }

            $expr = '/' . trim(str_replace('*', '(.*?)', str_replace('.', '\.', $filter))) . '/';
            if (preg_match($expr, $email)) {
                return false;
            }
        }

        return true;
    }

    public function isStringMatchingBlockedAlphabet(string $string): bool
    {
        foreach ($this->status->getBlockedAlphabets($this->scope) as $alphabet) {
            if (preg_match($alphabet, $string)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get first alphabet match for string
     */
    public function getAlphabetByString(string $string)
    {

        $blockedAlphabets = $this->status->getBlockedAlphabets($this->scope);
        foreach ($blockedAlphabets as $alphabet) {
            if (preg_match($alphabet, $string)) {
                preg_match('/{(.*?)}/', $alphabet, $match);
                return $match[1];
            }
        }

        return null;
    }
}
