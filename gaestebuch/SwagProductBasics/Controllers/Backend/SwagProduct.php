<?php

class Shopware_Controllers_Backend_SwagProduct extends Shopware_Controllers_Backend_Application
{

    protected $model = 'Shopware\CustomModels\Product\UserComment';
    protected $alias = 'product';


    // The function is called from it parents by an update or a create. It checks if an administrator or moderator is
    // set an direct to that function. Then it checks if the user already exists and if it changed from admin/mod to a
    // normal user. if the user doesnt exists ii creates one ig the username does not exists. Then it call the function
    // saveguestbookuser whitch saves an entry in s_plugin_guestbook_user.
    public function save($data){

        $data=$this->Request()->getParams();
        if(strtolower($data['rights'])=='administrator'||strtolower($data['rights'])=='moderator'){
            $this->administrator($data);
            return ;
        }

        if(!empty($data['id'])) {
            $guestbookrepository = Shopware()->Models()->getRepository('Shopware\CustomModels\Product\UserComment');
            $guestbookuser = $guestbookrepository->find($data['id']);

            if(strtolower($guestbookuser->getRights())=='moderator'||strtolower($guestbookuser->getRights())=='administrator'){
                $userrepository = Shopware()->Models()->getRepository('Shopware\Models\User\User');
                $user = $userrepository->findBy(array('username' => $guestbookuser->getUsername()));
                Shopware()->Models()->remove($user[0]);
                Shopware()->Models()->flush();

            }

        }
        else {
            $guestbookuser = new Shopware\CustomModels\Product\UserComment();
        }

        $this->saveguestbookuser($data,$guestbookuser);

    }


    // First the function checks wether a new entry for s_core_auth must be created or if an old one exists.
    // It then calls saveguestbook user whitch saves an entry in s_plugin_guestbook_user. After that it checks
    // wether a password is set or not. Then it saves the entry to the database.
    public function administrator($data){

        $guestbookrepository =Shopware()->Models()->getRepository('Shopware\CustomModels\Product\UserComment');

        if(!empty($data['id'])){
            $guestbookuser = $guestbookrepository->find($data['id']);
            $guestbookusername=$guestbookuser->getUsername();
            $guestbookpassword=$guestbookuser->getPassword();
            if(strtolower($guestbookuser->getRights())!='moderator'&&strtolower($guestbookuser->getRights())!='administrator')
            {
                $user = new Shopware\Models\User\User();
            }
            else {
                $userrepository = Shopware()->Models()->getRepository('Shopware\Models\User\User');
                $user= $userrepository->findBy(array('username' => $guestbookusername));
                $user=$user[0];
            }
            $this->saveguestbookuser($data,$guestbookuser);

        }
        else {
            $user = new Shopware\Models\User\User();
            $guestbookuser = new Shopware\CustomModels\Product\UserComment();
            $this->saveguestbookuser($data,$guestbookuser);

        }

        if(!empty($data['id'])&&empty($data['newpassword'])){
            $encoder = Shopware()->PasswordEncoder()->getDefaultPasswordEncoderName();
            $user->setPassword($guestbookpassword);
            $user->setEncoder($encoder);

        }
        else {
            $encoder = Shopware()->PasswordEncoder()->getDefaultPasswordEncoderName();
            $password = Shopware()->PasswordEncoder()->encodePassword($data['newpassword'], $encoder);
            $user->setPassword($password);
            $user->setEncoder($encoder);

        }
        if(strtolower($data['rights'])=='moderator'){
            $role=7;
        }
        else{
            $role=8;
        }
        $user->setRoleId($role);
        $user->setUsername($data['username']);
        $user->setLocaleId(0);
        $user->setActive(1);


        Shopware()->Models()->persist($user);
        Shopware()->Models()->flush();

        $this->View()->assign(array('success' => true));

    }

    //This function validates the password if the user exists and the password is set. After that a new guestbook entry
    // is created and the function createCustomer is called whitch create or update an entry in s_user and
    // s_user_address
    public function saveguestbookuser($data,$guestbookuser){
        
        $guestbookrepository = Shopware()->Models()->getRepository('Shopware\CustomModels\Product\UserComment');
        $username =$guestbookrepository->findBy(array('username' => $data['username']));
        if(!empty($username)&&$username[0]->getId()!=$data['id']){
            throw new Exception(
                'Username already exists'
            );
        }
        
        if(!filter_var($data['username'], FILTER_VALIDATE_EMAIL)){
            throw new Exception(
                'No validate Email Address'
            );
        }

        $password = $data['newpassword'];
        if(!empty($data['id'])&&empty($password)){


        }
        else {
            if (strlen($password) < 8) {
                $this->View()->assign(array('success' => false));
                throw new Exception(
                    'Password is to short'
                );
            } else if (0 === preg_match('~[0-9]~', $password) || 0 === preg_match('~[a-z]~', $password) || 0 === preg_match('~[A-Z]~', $password)) {
                $this->View()->assign(array('success' => false));
                throw new Exception(
                    'Password does not contain numbers,small or large letters'
                );
            }

            $encoder = Shopware()->PasswordEncoder()->getDefaultPasswordEncoderName();
            $password = Shopware()->PasswordEncoder()->encodePassword($password, $encoder);
            $guestbookuser->setPassword($password);

        }
        $oldguestbookusername=$guestbookuser->getUsername();

        $guestbookuser->setUsername($data['username']);
        $guestbookuser->setRights($data['rights']);
        Shopware()->Models()->persist($guestbookuser);
        Shopware()->Models()->flush();

        if(!empty($data['id'])){
            $customerrepository=Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer');
            $customer=$customerrepository->findBy(array('email'=> $oldguestbookusername));
            $this->createCustomer($data,$customer[0]);
        }
        else {
            $customer = new Shopware\Models\Customer\Customer();
            $this->createCustomer($data,$customer);
        }
        $this->View()->assign(array('success' => true));

    }

    // delete the entries, for a specific email in the tables s_core_auth, s_user and s_plugin_guestbook_user
    public function delete($id)
    {
        if (empty($id)) {
            return array('success' => false, 'error' => 'The id parameter contains no value.');
        }
        $guestbookrepository = Shopware()->Models()->getRepository('Shopware\CustomModels\Product\UserComment');
        $guestbookuser= $guestbookrepository->find($id);
        $customerrepository = Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer');
        $customer=$customerrepository->findBy(array('email' => $guestbookuser->getUsername()));
        if (empty($guestbookuser)) {
            return array('success' => false, 'error' => 'The passed id parameter exists no more.');
        }

        if(strtolower($guestbookuser->getRights())=='administrator'||strtolower($guestbookuser->getRights())=='moderator'){
            $userrepository =Shopware()->Models()->getRepository('Shopware\Models\User\User');
            $user=$userrepository->findBy(array('username' => $guestbookuser->getUsername()));
            Shopware()->Models()->remove($user[0]);
            Shopware()->Models()->flush();

        }

        Shopware()->Models()->remove($customer[0]);
        Shopware()->Models()->flush();
        Shopware()->Models()->remove($guestbookuser);
        Shopware()->Models()->flush();

        return array('success' => true);
    }

    //create or update the values in s_user and s_user_addresses. First sets the userinformation. Then inspect if there
    // is an address set. Create a new or use the existing one. Filled it with new values and save it. Open the existing
    // user and save the shipping and Billing address with the Address Object
    public function createCustomer($data,$customer){
        if(empty($data['newpassword'])){

        }
        else {
            $encoder = Shopware()->PasswordEncoder()->getDefaultPasswordEncoderName();
            $customerpassword = Shopware()->PasswordEncoder()->encodePassword($data['newpassword'], $encoder);
            $customer->setRawPassword($customerpassword);
            $customer->setEncoderName($encoder);
        }
        $customer->setEmail($data['username']);
        $customer->setActive(1);
        $customer->setLastname($data['Lastname']);
        $customer->setFirstname($data['Firstname']);
        $customer->setSalutation($data['salutation']);
        $incrementer = Shopware()->Container()->get('shopware.number_range_incrementer');
        $customer->setNumber($incrementer->increment('user'));
        Shopware()->Models()->persist($customer);
        Shopware()->Models()->flush();


        $customerrepository= Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer');
        $countryrepository= Shopware()->Models()->getRepository('Shopware\Models\Country\Country');
        $customer= $customerrepository->findBy(array('email'=> $data['username']));
        if(empty($customer[0]->getDefaultShippingAddress())){
            $address = new Shopware\Models\Customer\Address();
        }
        else{
            $addressrepository= Shopware()->Models()->getRepository('Shopware\Models\Customer\Address');
            $address=$addressrepository->findBy(array('customer'=> $customer[0]->getId()));
            $address=$address[0];

        }

        $address->setSalutation($data['salutation']);
        $address->setFirstname($data['Firstname']);
        $address->setLastname($data['Lastname']);
        $address->setStreet($data['street']);
        $address->setZipcode($data['zipcode']);
        $address->setCity($data['city']);

        $country= $countryrepository->findBy(array('name' => 'Deutschland'));
        $address->setCustomer($customer[0]);
        $address->setCountry($country[0]);
        Shopware()->Models()->persist($address);
        Shopware()->Models()->flush();

        $customerrepository= Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer');
        $addressrepository= Shopware()->Models()->getRepository('Shopware\Models\Customer\Address');

        $customer= $customerrepository->findBy(array('email'=> $data['username']));
        $address=$addressrepository->findBy(array('customer'=> $customer[0]->getId()));
        $customer[0]->setDefaultShippingAddress($address[0]);
        $customer[0]->setDefaultBillingAddress($address[0]);
        Shopware()->Models()->persist($customer[0]);
        Shopware()->Models()->flush();


    }


    //overrides the Parent Function. The Guestbookcustomer can load additional Data from the Table Customer, so the
    //backend can work with the additional Data
    protected function getAdditionalDetailData(array $data)
    {
        $customerrepository = Shopware()->Models()->getRepository('Shopware\Models\Customer\Customer');
        $addressrepository =Shopware()->Models()->getRepository('Shopware\Models\Customer\Address');
        $customer=$customerrepository->findBy(array('email'=> $data['username']));
        $address = $addressrepository->findBy(array('customer' => $customer[0]->getId()));
        $data['Firstname'] = $customer[0]->getFirstname();
        $data['Lastname'] = $customer[0]->getLastname();
        $data['salutation'] = $customer[0]->getSalutation();
        $data['street'] = $address[0]->getStreet();
        $data['zipcode'] = $address[0]->getZipcode();
        $data['city'] = $address[0]->getCity();
        return $data;
    }



}