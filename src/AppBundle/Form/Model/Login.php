<?php

namespace AppBundle\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

class Login
{
    /**
     * @Assert\Type(type="AppBundle\Entity\User")
     * @Assert\Valid()
     */
    protected $user;

    /**
     * @Assert\Email()
     */
    protected $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="4096")
     */
    protected $password;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}