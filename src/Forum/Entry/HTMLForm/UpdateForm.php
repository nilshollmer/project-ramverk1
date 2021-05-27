<?php

namespace Nihl\Forum\Entry\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Nihl\Forum\Entry\Entry;

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
        $entry = $this->getItemDetails($id);
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
                    "value" => $entry->id,
                ],

                "column1" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $entry->column1,
                ],

                "column2" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $entry->column2,
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
     * @return Entry
     */
    public function getItemDetails($id) : object
    {
        $entry = new Entry();
        $entry->setDb($this->di->get("dbqb"));
        $entry->find("id", $id);
        return $entry;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $entry = new Entry();
        $entry->setDb($this->di->get("dbqb"));
        $entry->find("id", $this->form->value("id"));
        $entry->column1 = $this->form->value("column1");
        $entry->column2 = $this->form->value("column2");
        $entry->save();
        return true;
    }



    // /**
    //  * Callback what to do if the form was successfully submitted, this
    //  * happen when the submit callback method returns true. This method
    //  * can/should be implemented by the subclass for a different behaviour.
    //  */
    // public function callbackSuccess()
    // {
    //     $this->di->get("response")->redirect("entry")->send();
    //     //$this->di->get("response")->redirect("entry/update/{$entry->id}");
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
