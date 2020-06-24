<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class NameValidatorTest extends TestCase
{

    /**
     * @var $objectManager
     */
    private $objectManager;

    /**
     * @var $model \PrOOxxy\SpamFilter\Model\Validator\NameValidator
     */
    private $model;

    public function setup()
    {
        parent::setUp();

        $this->objectManager = new ObjectManager($this);

        $this->model = $this->objectManager->getObject(
            \PrOOxxy\SpamFilter\Model\Validator\NameValidator::class,
            ['field' => 'firstname']
        );
    }

    /**
     * @test
     */
    public function isValid()
    {
        $strings = [
            'facebook.com' => false,
            'stringwithhttps://google.comstring' => false,
            'Johnny Bravo' => true,
            'string with https:// unfinished link' => false,
            'string . with .com stuff' => true
        ];

        foreach ($strings as $string => $assertion) {
            $this->assertEquals($this->model->isValid($string), $assertion);
        }
    }
}
