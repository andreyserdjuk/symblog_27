<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class FOSUserChild extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * Passport code
     *
     * @var string
     *
     * @ORM\Column(type="string", name="passport_code", length=20, nullable=true)
     */
    protected $passportCode='';

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->groups[] = $group;
    }

    /**
     * @return string
     */
    public function getPassportCode()
    {
        return $this->passportCode;
    }

    /**
     * @param string $passportCode
     */
    public function setPassportCode($passportCode)
    {
        $this->passportCode = $passportCode;
    }
}