<?php
namespace app\modules\Settings\interfaces;


interface UserRbacInterface {

    public function getId();
    public function getUserName();
    public static function findIdentity($id);
}