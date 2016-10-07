<?php
/**
 * Created by PhpStorm.
 * User: swilming
 * Date: 09.08.16
 * Time: 09:12
 */
namespace   Shopware\CustomModels\Product;

use         Shopware\Components\Model\ModelEntity,
    Doctrine\ORM\Mapping AS ORM;
use Shopware\Models\Blog\Comment;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_plugin_guestbook_user")
 *
 */

class UserComment extends ModelEntity
{
    /**
     * Primary Key - autoincrement value
     *
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $username
     * @ORM\Column(name="username",type="string", unique=true)
     */

    private $username;

    /**
     * @var string $password
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @var string $rights
     * @ORM\Column(name="rights", type="string")
     */

    private $rights;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username string
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * return string
     */

    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param string $rights
     */
    public function setRights($rights)
    {
        $this->rights = $rights;
    }

}