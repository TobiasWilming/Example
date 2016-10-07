<?php

class Shopware_Plugins_Backend_SwagProductBasics_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getInfo()
    {
        return array(
            'label' => 'Shopware Backend Guestbook'
        );
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SwagProduct',
            'getBackendController'
        );
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SwagComment',
            'getBackendControllerother'
        );

        $this->createMenuItem(array(
            'label' => 'Shopware Guestbookuser',
            'controller' => 'SwagProduct',
            'class' => 'sprite-application-block',
            'action' => 'Index',
            'active' => 1,
            'parent' => $this->Menu()->findOneBy(['label' => 'Marketing'])
        ));

        $this->createMenuItem(array(
            'label' => 'Shopware Comment',
            'controller' => 'SwagComment',
            'class' => 'sprite-application-block',
            'action' => 'Index',
            'active' => 1,
            'parent' => $this->Menu()->findOneBy(['label' => 'Marketing'])
        ));

        $this->addrules();

        //$this->addData();

        return true;
    }

    protected function updateSchema()
    {

    }
    //delete the acl resources for the administrator and Moderator from the Database
    public function uninstall()
    {
        $sql = "Select id From s_core_acl_resources WHERE name = ?";
        $commentId = Shopware()->Db()->fetchOne($sql,array('swagcomment'));
        $productId = Shopware()->Db()->fetchOne($sql,array('swagproduct'));

        $delete='Delete from s_core_acl_resources WHERE id= ? OR id = ?';
        Shopware()->Db()->query($delete, array($commentId,$productId));

        $delete = 'Delete from s_core_acl_privileges where resourceID = ? or resourceID = ?';
        Shopware()->Db()->query($delete, array($commentId,$productId));

        $delete= 'delete from s_core_acl_roles where resourceID = ? OR resourceID = ?';
        Shopware()->Db()->query($delete,array($commentId,$productId));

        return true;
    }

    public function getBackendController(Enlight_Event_EventArgs $args)
    {
        $this->Application()->Template()->addTemplateDir(
            $this->Path() . 'Views/'
        );

        $this->registerCustomModels();

        return $this->Path() . '/Controllers/Backend/SwagProduct.php';
    }
    public function getBackendControllerother(Enlight_Event_EventArgs $args)
    {
        $this->Application()->Template()->addTemplateDir(
            $this->Path() . 'Views/'
        );

        $this->registerCustomModels();

        return $this->Path() . '/Controllers/Backend/SwagComment.php';
    }

    // creates the rules for the Moderator and the andministrator
    public function addrules(){

        Shopware()->Acl()->createResource('swagproduct', array('read','create','update','delete'),'SwagProduct',$this->getId());
        Shopware()->Acl()->createResource('swagcomment', array('read','create','update','delete'),'SwagComment',$this->getId());

        $repositoryresouce = Shopware()->Models()->getRepository('Shopware\Models\User\Resource');

        $productId =$repositoryresouce->findBy(array('name' => 'swagproduct'));
        $commentId =$repositoryresouce->findBy(array('name' => 'swagcomment'));
        
        $entryrule = new Shopware\Models\User\Rule();
        $entryrule->setRoleId(7);
        $entryrule->setResourceId($commentId[0]->getID());
        Shopware()->Models()->persist($entryrule);
        Shopware()->Models()->flush();

        $entryrulepro = new Shopware\Models\User\Rule();
        $entryrulepro->setRoleId(8);
        $entryrulepro->setResourceId($commentId[0]->getID());
        Shopware()->Models()->persist($entryrulepro);
        Shopware()->Models()->flush();
        $entryrulecom = new Shopware\Models\User\Rule();
        $entryrulecom->setRoleId(8);
        $entryrulecom->setResourceId($productId[0]->getID());
        Shopware()->Models()->persist($entryrulecom);
        Shopware()->Models()->flush();
    }

   
}