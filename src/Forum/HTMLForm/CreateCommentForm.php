<?php

namespace Nihl\Forum\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Nihl\Forum\Comment\Comment;
use Nihl\Forum\Entry\Entry;
use Nihl\Forum\Question\Question;

/**
 * Form to create an item.
 */
class CreateCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $questionId, $username, $entryId = null)
    {
        parent::__construct($di);
        $extClassName = $entryId ? "\\" . $entryId : null;
        $this->form->create(
            [
                "id" => __CLASS__ . $extClassName,
                "class" => "comment-form",
                "use_fieldset" => false,
                "br-after-label" => false,
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
                "entry" => [
                    "type" => "hidden",
                    "value" => $entryId,
                ],
                "text" => [
                    "type" => "text",
                    "placeholder" => "Add a comment",
                    "class" => "form-element-comment",
                    "validation" => ["not_empty"],
                ],

                "Submit" => [
                    "type" => "submit",
                    "value" => "Post",
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
        $entryId    = $this->form->value("entry") ?: null;
        $text       = $this->form->value("text");

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->user  = $username;
        $comment->question = $questionId;
        $comment->entry = $entryId;
        $comment->text = $text;
        $comment->created = date('Y-m-d H:i:s');
        $comment->save();

        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirectSelf()->send();
        // $this->di->get("response")->redirect("comment")->send();
    }



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
