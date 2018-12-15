<?php
namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fiends
 * @ORM\Table(name="oc_team")
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Entity\AdvertRepository")
 */
class Team
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
   * @ORM\Column(name="userid", type="string", length=255)
   */
  private $userid;  
	
  /**
   * @ORM\Column(name="gradesid", type="string", length=255)
   */
  private $gradesid;  
	
  /**
   * @var int
   * @ORM\Column(name="friendswaitingid", type="integer")
   */
  private $friendswaitingid;  
	
  /**
   * @var int
   * @ORM\Column(name="advertid", type="string", length=255)
   */
  private $advertid;
	

    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return Userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set friendsid
     *
     * @param integer $gradesid
     *
     * @return grades
     */
    public function setGradesid($gradesid)
    {
        $this->gradesid = $gradesid;

        return $this;
    }

    /**
     * Get gradesid
     *
     * @return integer
     */
    public function getGradesid()
    {
        return $this->gradesid;
    }

    /**
     * Set friendswaitingid
     *
     * @param integer $friendswaitingid
     *
     * @return Friends
     */
    public function setFriendswaitingid($friendswaitingid)
    {
        $this->friendswaitingid = $friendswaitingid;

        return $this;
    }

    /**
     * Get friendswaitingid
     *
     * @return integer
     */
    public function getFriendswaitingid()
    {
        return $this->friendswaitingid;
    }    
	
	/**
     * Set advertid
     *
     * @param integer $advertid
     *
     * @return Advertid
     */
    public function setAdvertid($advertid)
    {
        $this->advertid = $advertid;

        return $this;
    }

    /**
     * Get advertid
     *
     * @return integer
     */
    public function getAdvertid()
    {
        return $this->advertid;
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
}
