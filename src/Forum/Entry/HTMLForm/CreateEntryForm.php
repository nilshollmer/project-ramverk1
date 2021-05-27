<?php

namespace Nihl\Forum\Entry\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Nihl\Forum\Entry\Entry;

/**
 * Form to create an item.
 */
class CreateEntryForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $questionId, $userId)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Your Answer",
            ],
            [
                "username" => [
                    "type" => "text",
                    "value" => $userId,
                ],
                "question" => [
                    "type" => "number",
                    "value" => $questionId,
                ],
                "text" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "description" => "<small>markdown is available</small>"
                ],

                "Submit" => [
                    "type" => "submit",
                    "value" => "Submit your answer",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        // Get values from form
        $user       = $this->form->value("username");
        $question   = $this->form->value("question");
        $text       = $this->form->value("text");

        $entry = new Entry();
        $entry->setDb($this->di->get("dbqb"));

        $entry->user  = $user;
        $entry->question = $question;
        $entry->text = $text;
        $entry->score = 0;
        $entry->created = date('Y-m-d H:i:s');
        $entry->save();

        $this->form->addOutput("Entry was created.");
        return true;
    }



    // /**
    //  * Callback what to do if the form was successfully submitted, this
    //  * happen when the submit callback method returns true. This method
    //  * can/should be implemented by the subclass for a different behaviour.
    //  */
    // public function callbackSuccess()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    //
    //     // $this->di->get("response")->redirect("forum/questions")->send();
    // }
    //
    //
    //
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
