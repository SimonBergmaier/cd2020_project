<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 13.04.2018
 * Time: 10:44
 */

namespace Blog;


class User extends Entity
{
    private $userName;
    private $passwordHash;

    public function __construct(int $id, string $userName, string $passwordHash)
    {
        parent::__construct($id);
        $this->userName=$userName;
        $this->passwordHash = $passwordHash;
    }

    public function getUserName() : string {
        return $this->userName;
    }

    public function getPasswordHash() : string {
        return $this->passwordHash;
    }
}