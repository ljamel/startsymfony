<?php
namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fiends
 * @ORM\Table(name="oc_friends_link")
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Entity\AdvertRepository")
 */
class Friends
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
   * @ORM\Column(name="userid", type="integer")
   */
  private $userid;  
	
  /**
   * @var int
   * @ORM\Column(name="friendsid", type="integer")
   */
  private $friendsid;  
	
  /**
   * @var int
   * @ORM\Column(name="friendswaitingid", type="integer")
   */
  private $friendswaitingid;
	

    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return Friends
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
     * @param integer $friendsid
     *
     * @return Friends
     */
    public function setFriendsid($friendsid)
    {
        $this->friendsid = $friendsid;

        return $this;
    }

    /**
     * Get friendsid
     *
     * @return integer
     */
    public function getFriendsid()
    {
        return $this->friendsid;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
