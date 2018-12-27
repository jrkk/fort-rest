<?php
namespace App\Entity;

use App\ORM\Entity;

/**
 * @table=users
 */
class User extends Entity  {

    /**
     * 
     * Auto sequence number.
     * no need to assing in insertion.
     * 
     * @Id
     * @Type=int
     * @Column=id
     * @Escape
     */
    var $id = 0;

    /**
     * @Column=username
     * @Type=DateTime
     */
    var $username;
    var $email;
    var $mobile;
    var $password;
    var $role;
    var $createddt;
    var $createdtm;
    var $updated;
    var $active;
    var $state;
}