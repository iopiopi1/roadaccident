<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity
 */
class Email
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="recipient", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $recipient;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="headers", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $headers;

    /**
     * @var string
     *
     * @ORM\Column(name="mailBody", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $mailBody;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModified", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateModified;

    /**
     * @var string
     *
     * @ORM\Column(name="extraHeader", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $extraHeader;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set recipient
     *
     * @param string $recipient
     *
     * @return Email
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient
     *
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set headers
     *
     * @param string $headers
     *
     * @return Email
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get headers
     *
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set mailBody
     *
     * @param string $mailBody
     *
     * @return Email
     */
    public function setMailBody($mailBody)
    {
        $this->mailBody = $mailBody;

        return $this;
    }

    /**
     * Get mailBody
     *
     * @return string
     */
    public function getMailBody()
    {
        return $this->mailBody;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Email
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Email
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     *
     * @return Email
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set extraHeader
     *
     * @param string $extraHeader
     *
     * @return Email
     */
    public function setExtraHeader($extraHeader)
    {
        $this->extraHeader = $extraHeader;

        return $this;
    }

    /**
     * Get extraHeader
     *
     * @return string
     */
    public function getExtraHeader()
    {
        return $this->extraHeader;
    }
}

