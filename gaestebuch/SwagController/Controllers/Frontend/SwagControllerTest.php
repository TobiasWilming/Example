<?php

class Shopware_Controllers_Frontend_SwagControllerTest extends Enlight_Controller_Action
{

    //address is not wuite fine


    //is called when the Controller is called without an action. It sets the Session page if it is empty amd after that
    // the function calls the show Comments function
    public function indexAction()
    {

        $newpage=$this->Request()->get('p');
        if(!empty($newpage)) {
            $page = $newpage;
        }
        else{
            $page=1;
        }
        
        $this->showComments($page);


    }

    // shows all Comments if you are a user who is loggt in in the shop. Show only the Comments which are active if you
    // are a guest. If the result is not empty the result is sliced intog pages and the Session page is increment or
    // decrement. Then the result is assign to the smarty Template.
    public function showComments($page){
        $this->View()->assign('commentsset','true');
        if($_SESSION['Shopware']['sUserId']){
            $repository = Shopware()->Models()->getRepository('Shopware\CustomModels\SwagController\ModelComments');
            $result = $repository->findBy(array(),array('id' => 'DESC'));

        }
        else{
            $repository = Shopware()->Models()->getRepository('Shopware\CustomModels\SwagController\ModelComments');
            $result = $repository->findBy(array('active' => '1'), array('id' => 'DESC'));
        }

        if(empty($result)){
        }
        else{
            $commentlenth=count($result);
            $result=array_slice($result,($page-1)*4,4);
            $this->View()->assign(array('page'=>$page,'pages' =>ceil($commentlenth/4),'comment'=>$result));
        }
    }

    //This function checks if send Button is set and if the field is not empty. Then it saves an Guestbook Comment with
    // active 1 if a user is loggt in or active 0 if a guest is sending the Comment. Then the showComment function is
    // called.
    public function sendCommentAction(){

        $this->View()->loadTemplate("frontend/swag_controller_test/index.tpl");
        if($this->Request()->get('send')&&!empty($this->Request()->get('comment'))) {

            $username=$this->getUsername();
            if(empty($username)){
                $this->showComments(1);
                $this->View()->assign(array('showalert'=>'true','alert'=>'cross',
                    'color'=>'error','entry'=>'Benutzer muss Eingetragen sein'));
                return ;
            }

            if($_SESSION['Shopware']['sUserId']!=''){
                $date=new \DateTime();
                $comment = new Shopware\CustomModels\SwagController\ModelComments();
                $comment->setComment($this->Request()->get('comment'));
                $comment->setActive('1');
                $comment->setUsername($username);
                $comment->setSenddata($date);
                Shopware()->Models()->persist($comment);
                Shopware()->Models()->flush();

            }
            else{
                $date=new \DateTime();
                $comment = new Shopware\CustomModels\SwagController\ModelComments();
                $comment->setComment($this->Request()->get('comment'));
                $comment->setActive('0');
                $comment->setUsername($username);
                $comment->setSenddata($date);
                Shopware()->Models()->persist($comment);
                Shopware()->Models()->flush();

            }

            $this->View()->assign(array('showalert'=>'true','alert'=>'check','color'=>'success',
                'entry'=>'Eintrag wurde erfolgreich gesendet'));
        }
        else{
            $this->View()->assign(array('showalert'=>'true','alert'=>'cross','color'=>'error',
                'entry'=>'Eintrag konnte nicht gesendet werden'));
        }
        $this->showComments(1);

    }

    // This function is a Helper function. It checks if the usernam field is set. if theats true the name in that
    // field will be returned. Or if the user is loggt in his first and last name will be returned. If non of that
    // is true the function gives an empty string back 
    public function getUsername(){
        if(!empty($this->Request()->get('username'))){
            $username=$this->Request()->get('username');
            return $username;
        }
        else if(!empty($_SESSION['Shopware']['sUserId'])){
            $repository=Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer');
            $customer=$repository->findBy(array('email' => $_SESSION['Shopware']['sUserMail']));
            $username = ''.$customer[0]->getFirstname().' '.$customer[0]->getLastname();
            return $username;
        }
        else{
            $username='';
            return $username;
        }
        
        
        
    }

}
