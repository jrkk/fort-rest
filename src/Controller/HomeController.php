<?php
namespace App\Controller;

use App\Core\Controller;
use App\Entity\User;

class HomeController extends Controller {
    
    public function start() {

        $this->load('entity', User::class, 'user');

        $this->user->username = 'Jrk Kiran';
        $this->user->email = 'kiranjrkk@gmail.com';
        $this->user->mobile = 9676640228;
        $this->user->role = 'admin';

        $this->user->remove();
        //$this->user->update(['username','email']);

        $this->user->id = 11;
        //$this->user->findById();

        $this->response
            ->setStatus(200)
            ->setContentType('text/plain')
            ->setSuccessState()
            ->setResponse("name","jrkkiran");
    }

}