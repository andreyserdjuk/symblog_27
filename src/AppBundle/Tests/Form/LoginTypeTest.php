<?php

namespace AppBundle\Tests\Form;

use AppBundle\Form\LoginType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class LoginTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@test.com',
            'password' => '12345'
        ];

        $type = new LoginType();
        $form = $this->factory->create($type);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($formData, $form->getData());
    }

    protected function getExtensions()
    {
        return array_merge(parent::getExtensions(), array(
            new ValidatorExtension(Validation::createValidator()),
        ));
    }
}
