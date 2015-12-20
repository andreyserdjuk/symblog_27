<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MatrixFormType
 * @package AppBundle\Form
 *
 * @ORM\Entity
 * @ORM\Table(name="status_w_d")
 */
class StatusWorkflowDefinition
{
    protected static $allStatuses = [
       'apending' => 'App pending',
       'bpending' => 'Be pending',
       'cpending' => 'Cust pending',
       'dpending' => 'Dummy pending',
       'vpending' => 'Virt pending',
    ];

    /**
     * @var Group
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Group", inversedBy="statusWorkflowDefinitions")
     */
    protected $group;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="current_status", type="string")
     */
    protected $currentStatus;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="next_status", type="string")
     */
    protected $nextStatus;

    /**
     * @var boolean
     * @ORM\Column(name="is_mandatory_comment", type="boolean")
     */
    protected $mandatoryComment;

    /**
     * @return mixed
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * @param mixed $currentStatus
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;
    }

    /**
     * @return mixed
     */
    public function getNextStatus()
    {
        return $this->nextStatus;
    }

    /**
     * @param mixed $nextStatus
     */
    public function setNextStatus($nextStatus)
    {
        $this->nextStatus = $nextStatus;
    }

    /**
     * @return mixed
     */
    public function isMandatoryComment()
    {
        return $this->mandatoryComment;
    }

    /**
     * @param mixed $isMandatoryComment
     */
    public function setIsMandatoryComment($isMandatoryComment)
    {
        $this->mandatoryComment = $isMandatoryComment;
    }

    /**
     * @return array
     */
    public static function getAllStatuses()
    {
        return self::$allStatuses;
    }
}