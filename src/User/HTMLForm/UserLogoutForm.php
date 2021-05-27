<?php

namespace Nihl\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

/**
 *  User logout
 */
class UserLogoutForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Sign out"
            ],
            [
                "submit" => [
                    "type" => "submit",
                    "value" => "Sign out",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        // $username       = $this->form->value("username");
        // $password      = $this->form->value("password");

        $user = new \Nihl\User\User();
        $user->setDb($this->di->get("dbqb"));
        // $res = $user->verifyPassword($username, $password);
        //
        // if (!$res) {
        //     $this->form->rememberValues();
        //     $this->form->addOutput("User or password did not match.");
        //     return false;
        // }
        $this->di->get("session")->delete("loggedin");

        return true;
    }
}
