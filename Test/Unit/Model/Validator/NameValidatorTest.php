<?php

namespace PrOOxxy\SpamFilter\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class NameValidatorTest extends TestCase
{

    /**
     * @var $model \PrOOxxy\SpamFilter\Model\Validator\NameValidator
     */
    private $model;

    public function setup()
    {
        parent::setUp();

        $objectManager = new ObjectManager($this);

        $this->model = $objectManager->getObject(
            \PrOOxxy\SpamFilter\Model\Validator\NameValidator::class,
            ['field' => 'firstname']
        );
    }

    /**
     * @test
     * @dataProvider isValidDataProvider
     */
    public function isValid(string $input, bool $assert)
    {
        $this->assertEquals($this->model->isValid($input), $assert);
    }

    public function isValidDataProvider()
    {
        return [
            'Should detect FQDN'                        => ['input' => 'facebook.com','assert' => false],
            'Should detect hidden full url'             => ['input' => 'stringwithhttps://google.comstring','assert' => false],
            'Should detect unfinished url'              => ['input' => 'string with https:// unfinished link','assert' => false],
            'Should not detect non existing'            => ['input' => 'Johnny Bravo','assert' => true],
            'Should not detect fragments'               => ['input' => 'string . with .com stuff','assert' => true]
        ];
    }
}
