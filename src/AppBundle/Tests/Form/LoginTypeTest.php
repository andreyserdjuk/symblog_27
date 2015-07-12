<?php

namespace AppBundle\Tests\Form;


use AppBundle\Form\LoginType;
use AppBundle\Form\Model\Login;
use Symfony\Component\Form\Tests\Extension\Validator\Type\TypeTestCase;

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

        $object = Login::fromArray($formData);
    }
}
