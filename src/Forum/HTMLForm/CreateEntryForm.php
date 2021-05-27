<?php

namespace Nihl\Forum\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Nihl\Forum\Entry\Entry;
use Nihl\Forum\Question\Question;

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
    public function __construct(ContainerInterface $di, $questionId, $username)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Your Answer",
                "class" => "entry-form",
                "use_fieldset" => false,
                "escape-values" => false
            ],
            [
                "username" => [
                    "type" => "hidden",
                    "value" => $username,
                ],
                "question" => [
                    "type" => "hidden",
                    "value" => $questionId,
                ],
                "text" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "description" => "You can write you answer markdown"
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
        $username   = $this->form->value("username");
        $questionId = $this->form->value("question");
        $text       = $this->form->value("text");

        $entry = new Entry();
        $entry->setDb($this->di->get("dbqb"));

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->findById($questionId);

        $entry->user  = $username;
        $entry->question = $questionId;
        $entry->text = $text;
        $entry->score = 0;
        $entry->created = date('Y-m-d H:i:s');
        $entry->save();

        $question->incrementEntries();
        $question->save();

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
    //     // $this->di->get("response")->redirectSelf()->send();
    //
    //     $this->di->get("response")->redirect("forum/questions/1")->send();
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
    //
    // }
}
