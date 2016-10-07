<?php
use Shopware\SwagController\AccountBundle\RegisterServicese;
use Shopware\SwagController\AccountBundle\CustomerService;

/**
 * The Bootstrap class is the main entry point of any shopware plugin.
 *
 * Short function reference
 * - install: Called a single time during (re)installation. Here you can trigger install-time actions like
 *   - creating the menu
 *   - creating attributes
 *   - creating database tables
 *   You need to return "true" or array('success' => true, 'invalidateCache' => array()) in order to let the installation
 *   be successfull
 *
 * - update: Triggered when the user updates the plugin. You will get passes the former version of the plugin as param
 *   In order to let the update be successful, return "true"
 *
 * - uninstall: Triggered when the plugin is reinstalled or uninstalled. Clean up your tables here.
 */
class Shopware_Plugins_Frontend_SwagController_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getVersion()
    {
        $info = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .'plugin.json'), true);
        if ($info) {
            return $info['currentVersion'];
        } else {
            throw new Exception('The plugin has an invalid version file.');
        }
    }

    public function getLabel()
    {
        return 'SwagController';
    }

    public function uninstall()
    {
        $sql = "
           DELETE from s_core_auth WHERE username in
           (SELECT
               a.username
           FROM s_plugin_guestbook_user a)
       ";
        Shopware()->Db()->query($sql);
        $this->registerCustomModels();

        $em = $this->Application()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\SwagController\ModelComments'),
            $em->getClassMetadata('Shopware\CustomModels\SwagController\UserComment')
        );
        $tool->dropSchema($classes);


        return true;
    }

    public function update($oldVersion)
    {
        return true;
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend',
            'onFrontendPostDispatch'
        );

        $this->registerController('Frontend', 'SwagControllerTest');
        $this->updateSchema();
        $this->addDemoData();

        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_account.register_service',
            'decorateService',
            600
        );
        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_account.customer_service',
            'decorateEmailService',
            600
        );

        return true;
    }

    /**
     * Creates the database scheme from an existing doctrine model.
     *
     * Will remove the table first, so handle with care.
     */
    protected function updateSchema()
    {
        $this->registerCustomModels();

        $em = $this->Application()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\SwagController\ModelComments'),
            $em->getClassMetadata('Shopware\CustomModels\SwagController\UserComment')
        );

        try {
            $tool->dropSchema($classes);
        } catch (Exception $e) {
            //ignore
        }
        $tool->createSchema($classes);
    }
    public function onFrontendPostDispatch(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $view->addTemplateDir(
            __DIR__ . '/Views'
        );

        $view->extendsTemplate('frontend/swag_controller_test/navigation.tpl');

    }

    public function decorateEmailService(){
        $coreService=Shopware()->Container()->get('shopware_account.customer_service');
        $newService = new CustomerService ($coreService);
        Shopware()->Container()->set('shopware_account.customer_service',$newService);
    }


    public function decorateService(){

        $coreService= Shopware()->Container()->get('shopware_account.register_service');
        $newService = new RegisterServicese ($coreService);
        Shopware()->Container()->set('shopware_account.register_service', $newService);

    }
    public function afterInit(){
        $this->get('Loader')->registerNamespace(
            'Shopware\SwagController',
            $this->Path()
        );
        $this->registerCustomModels();
    }

    protected function addDemoData()
   {
       $sql = "
           INSERT IGNORE INTO s_plugin_guestbook_user (username, password)
           SELECT
               a.email as username,
               a.password
           FROM s_user a
       ";
       Shopware()->Db()->query($sql);
   }
    

}