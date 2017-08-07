<?php

namespace Cron\CronBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CronReport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cron\CronBundle\Repository\CronReportRepository")
 */
class CronReport
{
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime $runAt
     */
    protected $runAt;
    /**
     * @ORM\Column(type="float")
     * @var float $runTime
     */
    protected $runTime;
    /**
     * @ORM\Column(type="integer")
     * @var integer $result
     */
    protected $exitCode;
    /**
     * @ORM\Column(type="text")
     * @var string $output
     */
    protected $output;
    /**
     * @ORM\ManyToOne(targetEntity="CronJob", inversedBy="reports")
     * @var CronJob
     */
    protected $job;
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return CronJob
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param CronJob $job
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @param int $exitCode
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;
    }

    /**
     * @return float
     */
    public function getRunTime()
    {
        return $this->runTime;
    }

    /**
     * @param float $runTime
     */
    public function setRunTime($runTime)
    {
        $this->runTime = $runTime;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getRunAt()->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getRunAt()
    {
        return $this->runAt;
    }

    /**
     * @param \DateTime $runAt
     */
    public function setRunAt($runAt)
    {
        $this->runAt = $runAt;
    }
}
