<?php
/**
 * Created by PhpStorm.
 * User: swilming
 * Date: 09.08.16
 * Time: 09:12
 */
namespace   Shopware\CustomModels\SwagController;

use         Shopware\Components\Model\ModelEntity,
    Doctrine\ORM\Mapping AS ORM;
use Shopware\Models\Blog\Comment;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_plugin_guestbook_comments")
 *
 */

class ModelComments extends ModelEntity
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
     * @var string $comment
     * @ORM\Column(name="comment",type="string")
     */

    private $comment;

    /**
     * @var boolean $active
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string $username
     * @ORM\Column(name="username", type="string")
     */
    private $username;

    /**
     * @var \DateTime $senddate
     * @ORM\Column(name="senddate",type="date")
     */
    private $senddate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(){

        return $this->username;

    }
    /**
     * @param $username string
     */
    public function setUsername($username){

        $this->username=$username;
    }
    /**
     * @return \DateTime
     */
    public function getSenddate(){
        return $this->senddate;
    }
    /**
     * @param \DateTime|string $senddate
     * @return ModelComments
     */
    public function setSenddata($senddate)
    {
        if (!$senddate instanceof \DateTime) {
            $senddate = new \DateTime($senddate);
        }
        $this->senddate = $senddate;
        return $this;
    }


    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param $comment string
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return boolean
     */

    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }


}