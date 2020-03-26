<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee
{
    /**
     * @Groups({"employees", "jobs"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"employees", "jobs"})
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @Groups({"employees", "jobs"})
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @Groups({"employees", "jobs"})
     * @ORM\Column(type="date")
     */
    private $employementDate;

    /**
     * @Groups({"employees"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmployementDate(): ?\DateTimeInterface
    {
        return $this->employementDate;
    }

    public function setEmployementDate(\DateTimeInterface $employementDate): self
    {
        $this->employementDate = $employementDate;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }
}
