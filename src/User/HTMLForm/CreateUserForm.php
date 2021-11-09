<?php

namespace Nihl\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

/**
 * Implementing Create user
 */
class CreateUserForm extends FormModel
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
                "legend" => "Legend",
                "use_fieldset" => false,
                "escape-values" => false
            ],
            [
                "username" => [
                    "type"        => "text",
                    "validation" => ["not_empty"],
                ],

                "email" => [
                    "type"        => "email",
                    "validation" => ["not_empty"],
                ],

                "password" => [
                    "type"        => "password",
                    "validation" => ["not_empty"],
                ],

                "verify-password" => [
                    "type"        => "password",
                    "validation" => ["not_empty"],
                ],

                "occupation" => [
                    "type"        => "text",
                ],

                "location" => [
                    "type"        => "text",
                ],
                "submit" => [
                    "type" => "submit",
                    "value" => "Sign up",
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
        $username       = $this->form->value("username");
        $email          = $this->form->value("email");
        $password       = $this->form->value("password");
        $verifyPassword = $this->form->value("verify-password");
        $occupation     = $this->form->value("occupation");
        $location       = $this->form->value("location");

        // Check password matches
        if ($password !== $verifyPassword ) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        // // Save to database
        // $db = $this->di->get("dbqb");
        // $password = password_hash($password, PASSWORD_DEFAULT);
        // $db->connect()
        //    ->insert("User", ["username", "password"])
        //    ->execute([$username, $password]);

        $user = new \Nihl\User\User();
        $user->setDb($this->di->get("dbqb"));
        $user->username = $username;
        $user->email = $email;
        $user->occupation = $occupation;
        $user->location = $location;
        $user->reputation = 0;
        $user->created = date('Y-m-d H:i:s');
        $user->setPassword($password);
        $user->save();

        $this->form->addOutput("User was created.");
        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("user/login")->send();
    }
}
