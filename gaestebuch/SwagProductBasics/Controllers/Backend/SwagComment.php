<?php


class Shopware_Controllers_Backend_SwagComment extends Shopware_Controllers_Backend_Application
{
    protected $model = 'Shopware\CustomModels\Product\ModelComments';
    protected $alias = 'comment';

    // This function checks if there is a coment that is updated or new. It then saves the Data into the table.
    public function save($data){
        $date=new \DateTime();

        if(!empty($data['id'])) {
            $guestbookrepository = Shopware()->Models()->getRepository('Shopware\CustomModels\Product\ModelComments');
            $comment = $guestbookrepository->find($data['id']);
            
        }
        else {
            $comment = new Shopware\CustomModels\Product\ModelComments();
        }
        $comment->setActive($data['active']);
        $comment->setComment($data['comment']);
        $comment->setSenddata($date);
        $comment->setUsername($_SESSION['Shopware']['Auth']->username);

        Shopware()->Models()->persist($comment);
        Shopware()->Models()->flush();

        $this->View()->assign(array('success' => true));
    }

    //It finds the Comment with the right id. It the changes the value of active from true to false or the other
    // way round.
    public function savemanyAction(){
        $data=$this->Request()->getParam('datan');

        $guestbookrepository = Shopware()->Models()->getRepository('Shopware\CustomModels\Product\ModelComments');
        $comment= $guestbookrepository->find($data);
        if($comment->getActive()){
            $comment->setActive(false);
        }
        else{
            $comment->setActive(true);
        }
        Shopware()->Models()->persist($comment);
        Shopware()->Models()->flush();
        
        $this->View()->assign(array('success' => true));
    }
    
}