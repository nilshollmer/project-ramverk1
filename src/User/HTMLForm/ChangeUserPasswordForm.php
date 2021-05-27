<?php

namespace Nihl\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Nihl\User\User;

/**
 * Implementing Create user
 */
class UpdateUserForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $user = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Legend",
                "use_fieldset" => false,
                "class" => "entry-form",
                "escape-values" => false
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $user->id,
                ],

                "old password" => [
                    "type"        => "password",
                ],

                "new password" => [
                    "type"        => "password",
                ],

                "verify-password" => [
                    "type"        => "password",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save changes",
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
        $user               = new \Nihl\User\User();
        $user               ->setDb($this->di->get("dbqb"));
        $user               ->find("id", $this->form->value("id"));

        // Get values from the submitted form
        $username           = $this->form->value("username");
        $oldPassword        = $this->form->value("old password");
        $newPassword        = $this->form->value("new password");
        $verifyPassword     = $this->form->value("verify-password");

        if (isset($newPassword)) {
            $res = $user->verifyPassword($username, $oldPassword);

            if (!$res) {
                $this->form->rememberValues();
                $this->form->addOutput("Old password was not correct");
                return false;
            }

            // Check password matches
            if ($newPassword !== $verifyPassword ) {
                $this->form->rememberValues();
                $this->form->addOutput("Passwords did not match.");
                return false;
            }

        }

        $user->setPassword($newPassword);
        $user->save();
        $this->form->addOutput("User was updated.");
        return true;
    }


    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return User
     */
    public function getItemDetails($id) : object
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $id);
        return $user;
    }
    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {

        $this->di->get("response")->redirect("users/" . $this->form->value("username"))->send();
    }

}
