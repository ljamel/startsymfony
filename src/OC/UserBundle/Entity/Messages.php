<?php

namespace OC\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use OC\PlatformBundle\Validator\Antiflood;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Table(name="oc_messages")
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Messages
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;  
	
  /**
   * @var int
   * @ORM\ManyToMany(targetEntity="OC\UserBundle\Entity\User", cascade={"persist"})
   * @ORM\JoinTable(name="oc_messages_link")
   */
  private $usersend;  
	
  /**
   * @var int
   *
   * @ORM\Column(name="userreceived", type="string", length=255)
   */
  private $userreceived;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date", type="datetime")
   * @Assert\DateTime()
   */
  private $date;

  /**
   * @var string
   *
   * Et pour être logique, il faudrait aussi mettre la colonne titre en Unique pour Doctrine :
   * @ORM\Column(name="title", type="string", length=255)
   */
  private $title;

  /**
   * @var string
   *
   * @ORM\Column(name="author", type="string", length=255)
   * @Assert\Length(min=2)
   */
  private $author;  
	
  /**
   * @var string
   *
   * @ORM\Column(name="content", type="string", length=255)
   * @Assert\Length(min=2)
   */
  private $content;


	public function __construct()
    {
		$this->date = new \Datetime();
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
     * Set usersend
     *
     * @param integer $usersend
     *
     * @return Messages
     */
    public function setUsersend($usersend)
    {
        $this->usersend = $usersend;

        return $this;
    }

    /**
     * Get usersend
     *
     * @return integer
     */
    public function getUsersend()
    {
        return $this->usersend;
    }

    /**
     * Set userreceived
     *
     * @param integer $userreceived
     *
     * @return Messages
     */
    public function setUserreceived($userreceived)
    {
        $this->userreceived = $userreceived;

        return $this;
    }

    /**
     * Get userreceived
     *
     * @return integer
     */
    public function getUserreceived()
    {
        return $this->userreceived;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Messages
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Messages
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Messages
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Messages
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
