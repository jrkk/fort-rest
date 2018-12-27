<?php
namespace App\Controller;

use App\Core\Controller;
use App\Entity\User;

class HomeController extends Controller {
    
    public function start() {

        $this->load('entity', User::class, 'user');

        $this->user->update();

        $this->response
            ->setStatus(200)
            ->setContentType('text/plain')
            ->setSuccessState()
            ->setResponse("name","jrkkiran");
    }

}