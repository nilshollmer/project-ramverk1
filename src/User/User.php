<?php

namespace Nihl\User;


use Anax\DatabaseActiveRecord\ActiveRecordModel;


/**
 * Database driven user model
 */
class User extends ActiveRecordModel
{

    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "User";


    /**
     * Columns in table
     * @var integer     $id
     * @var string      $username
     * @var string      $email
     * @var string      $password
     * @var integer     $reputation
     * @var datetime    $created
     * @var datetime    $updated
     * @var datetime    $deleted
     * @var datetime    $active
     */
    public $id;
    public $username;
    public $email;
    public $password;
    public $reputation;
    public $created;
    public $updated;
    public $deleted;
    public $active;

    /**
     * Set the password.
     *
     * @param string $password the password to use.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }



    /**
     * Verify the username and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $username  username to check.
     * @param string $password the password to use.
     *
     * @return boolean true if username and password matches, else false.
     */
    public function verifyPassword($username, $password)
    {
        $this->find("username", $username);
        return password_verify($password, $this->password);
    }
}
