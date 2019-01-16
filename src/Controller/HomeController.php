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
        $this->user->password = md5($this->user->mobile);

        $cdt = new \DateTime('now');
        $this->user->createddt = $cdt->format('Y-m-d');
        $this->user->createdtm = $cdt->format('H:i:s');

        //$this->user->save();

        $this->user->id = 1;
        //$this->user->update(['password']);

        $this->user->findById();
        //$this->user->remove();
        //$this->user->findById();

        $this->response
            ->setStatus(200)
            ->setContentType('text/plain')
            ->setSuccessState()
            ->setResponse("name","jrkkiran");
    }

}