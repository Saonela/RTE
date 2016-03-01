<?php

namespace RTER\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\OneToMany(targetEntity="RTER\ContentBundle\Entity\BlogPost", mappedBy="user")
    */
    protected $blogPosts;

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogPosts()
    {
        return $this->blogPosts;
    }

    /**
     * @param mixed $blogPosts
     */
    public function setBlogPosts($blogPosts)
    {
        $this->blogPosts = $blogPosts;
    }


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}

