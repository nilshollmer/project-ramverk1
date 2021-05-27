<?php

namespace Nihl\Forum\Question2Tag\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Nihl\Forum\Question2Tag\Question2Tag;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $question2Tag = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update details of the item",
            ],
            [
                "id" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $question2Tag->id,
                ],

                "column1" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $question2Tag->column1,
                ],

                "column2" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $question2Tag->column2,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type"      => "reset",
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     * 
     * @return Question2Tag
     */
    public function getItemDetails($id) : object
    {
        $question2Tag = new Question2Tag();
        $question2Tag->setDb($this->di->get("dbqb"));
        $question2Tag->find("id", $id);
        return $question2Tag;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $question2Tag = new Question2Tag();
        $question2Tag->setDb($this->di->get("dbqb"));
        $question2Tag->find("id", $this->form->value("id"));
        $question2Tag->column1 = $this->form->value("column1");
        $question2Tag->column2 = $this->form->value("column2");
        $question2Tag->save();
        return true;
    }



    // /**
    //  * Callback what to do if the form was successfully submitted, this
    //  * happen when the submit callback method returns true. This method
    //  * can/should be implemented by the subclass for a different behaviour.
    //  */
    // public function callbackSuccess()
    // {
    //     $this->di->get("response")->redirect("question2Tag")->send();
    //     //$this->di->get("response")->redirect("question2Tag/update/{$question2Tag->id}");
    // }



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    // }
}
