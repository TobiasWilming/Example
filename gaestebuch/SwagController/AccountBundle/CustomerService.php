<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\SwagController\AccountBundle;

use Shopware\Bundle\AccountBundle\Service\Validator\CustomerValidatorInterface;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Customer\Customer;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\Collection;
use Shopware\Bundle\AccountBundle\Service\CustomerServiceInterface;

class CustomerService implements CustomerServiceInterface
{

    /**
     * @var CustomerServiceInterface
     */
    private $service;

    /**
     * @param CustomerServiceInterface $service
     */
    public function __construct(CustomerServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param Customer $customer
     * @return Customer
     * @throws ValidationException
     */
    public function update(Customer $customer)
    {
        
        $repository=Shopware()->Models()->getRepository('Shopware\CustomModels\SwagController\UserComment');
        $guestbookuser=$repository->findBy(array('username'=> $_SESSION['Shopware']['sUserMail']));
        $guestbookuser[0]->setUsername($customer->getEmail());
        Shopware()->Models()->persist($guestbookuser[0]);
        Shopware()->Models()->flush();

        if($guestbookuser[0]->getRights()=='Moderator'||$guestbookuser[0]->getRights()=='Administrator'){
            $repository=Shopware()->Models()->getRepository('Shopware\Models\User\User');
            $user=$repository->findBy(array('username'=> $_SESSION['Shopware']['sUserMail']));
            $user[0]->setUsername($customer->getEmail());
            Shopware()->Models()->persist($user[0]);
            Shopware()->Models()->flush();
        }


        $this->service->update($customer);

    }
}
