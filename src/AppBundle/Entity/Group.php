<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_group")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\StatusWorkflowDefinition", mappedBy="group")
     */
    protected $statusWorkflowDefinitions;

    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
        $this->statusWorkflowDefinitions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getStatusWorkflowDefinitions()
    {
        return $this->statusWorkflowDefinitions->toArray();
    }

    /**
     * @param StatusWorkflowDefinition $statusWorkflowDefinition
     */
    public function setStatusWorkflowDefinition(StatusWorkflowDefinition $statusWorkflowDefinition)
    {
        $this->statusWorkflowDefinitions[] = $statusWorkflowDefinition;
    }
}