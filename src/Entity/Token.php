<?php
namespace App\Entity;

use App\ORM\Entity;

/**
 * @Table=mydb.users
 */
class Token extends Entity  {

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
     * @Type=string
     */
    var $username;

    /**
     * @Column=email
     * @Type=string
     */
    var $email;

    /**
     * @Column=mobile
     * @Type=string
     */
    var $mobile;

    /**
     * @Column=password
     * @Type=string
     */
    var $password;

    /**
     * @Column=role
     * @Type=string
     */
    var $role = 'USER';

    /**
     * @Column=createddt
     * @Type=DateTime
     */
    var $createddt = null;

    /**
     * @Column=createdtm
     * @Type=DateTime
     */
    var $createdtm = null;

    /**
     * @Nullable
     */
    var $updated;

    /**
     * @Column=active
     * @Type=int
     */
    var $active = 0;

    /**
     * @Column=state
     * @Type=int
     */
    var $state = 1;

    
}