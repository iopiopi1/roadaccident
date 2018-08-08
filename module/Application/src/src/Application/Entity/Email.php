<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 28.02.2016
 * Time: 15:12
 */

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="email")
 */
class Email
{
			
    const STATUS_CREATED = 0;
    const STATUS_SENT = 1;
    const STATUS_ERROR = 2;
	
    /**
     * Primary Identifier
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $recipient;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $headers;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $mailBody;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $dateCreated;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $dateModified;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $extraHeader;
	
    public function __construct()
    {
        $this->setDateCreated(new \DateTime("now"));
        $this->setDateModified(new \DateTime("now"));
    }
	
	
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