<?php
namespace App\Controller;

use App\Config\RestConfig;

use App\Core\System;
use App\Core\Controller;
use App\Entity\User;

class HomeController extends Controller {
    
    public function start() {
        
        RestConfig::Oauth && $this->isAuthorized();

        $user = System::load('user', User::class);

        $user->username = 'Jrk Kiran';
        $user->email = 'kiranjrkk@gmail.com';
        $user->mobile = 9676640228;
        $user->role = 'admin';
        $user->password = md5($user->mobile);

        $cdt = new \DateTime('now');
        $user->createddt = $cdt->format('Y-m-d');
        $user->createdtm = $cdt->format('H:i:s');

        //$this->user->save();

        $user->id = 1;
        //$this->user->update(['password']);

        $user->findById();
        //$this->user->remove();
        //$this->user->findById();

        $this->response
            ->setStatus(200)
            ->setContentType('text/plain')
            ->setSuccessState()
            ->setResponse("name","jrkkiran");
    }

}