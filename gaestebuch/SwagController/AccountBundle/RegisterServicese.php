<?php

namespace Shopware\SwagController\AccountBundle;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\AccountBundle\Service\RegisterServiceInterface;
use Shopware\Bundle\AccountBundle\Service\Validator\CustomerValidatorInterface;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ContextService;
use Shopware\Bundle\StoreFrontBundle\Struct\Shop;
use Shopware\Components\DependencyInjection\Bridge\Models;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\NumberRangeIncrementerInterface;
use Shopware\Components\Password\Manager;
use Shopware\Models\Customer\Address;
use Shopware\Models\Customer\Customer;
use Shopware_Components_Config;
use Symfony\Component\Validator\Constraints\Collection;
use Shopware\Models\Partner\Partner;
use Shopware\CustomModels\SwagController\UserComment;


class RegisterServicese implements RegisterServiceInterface
{

    /**
     * @var RegisterServiceInterface
     */
    private $service;

    /**
     * @param RegisterServiceInterface $service
     */
    function __construct(RegisterServiceInterface $service)
    {
        $this->service = $service;

    }


    public function register(
        Shop $shop,
        Customer $customer,
        Address $billing,
        Address $shipping = null
    ) {
        
        $this->service->register($shop,$customer,$billing,$shipping);
        $this->saveGuestbook($customer);
    }

    public function saveGuestbook(Customer $customer){
        $guestbookuser = new UserComment();
        $guestbookuser->setUsername($customer->getEmail());
        $guestbookuser->setRights('user');
        $guestbookuser->setPassword($customer->getPassword());
        Shopware()->Models()->persist($guestbookuser);
        Shopware()->Models()->flush();

    }


}