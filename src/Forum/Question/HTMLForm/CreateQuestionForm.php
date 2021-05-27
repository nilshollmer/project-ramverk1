<?php

namespace Nihl\Forum\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Nihl\Forum\Question\Question;
use Nihl\Forum\Question2Tag\Question2Tag;
use Nihl\Forum\Tag\Tag;

/**
 * Form to create an item.
 */
class CreateQuestionForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $username)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "class" => "question-form",
                "use_fieldset" => false,
                "escape-values" => false
            ],
            [
                "user" => [
                    "type" => "hidden",
                    "value" => $username,
                ],

                "title" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "description" => "Be specific and imagine you’re asking a question to another person"
                ],

                "text" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "description" => "Include all the information someone would need to answer your question"

                ],

                "tags" => [
                    "type" => "text",
                    "description" => "Add up to 5 tags to describe what your question is about, separated by comma",
                    "placeholder" => "e.g. flowers, chrysanthemum, compost, perennials, tools"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Create Question",
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
        $title  = $this->form->value("title");
        $user   = $this->form->value("user");
        $text   = $this->form->value("text");

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $question->user = $user;
        $question->title = $title;
        $question->text = $text;

        $question->views = 0;
        $question->score = 0;
        $question->entries = 0;
        $question->created = date('Y-m-d H:i:s');
        $question->save();
        // $this->createQuestion($title, $user, $text);

        $questionId = $question->getIdFromTitleAndUsername($title, $user);

        $tags = explode(",", $this->form->value("tags"));

        foreach($tags as $t) {
            $tag = new Tag();
            $tag->setDb($this->di->get("dbqb"));
            $tagName = strtolower(trim($t));
            $tag->find("name", $tagName);
            if (!$tag->name) {
                $tag->name = $tagName;
                $tag->save();
            }
            $question2Tag = new Question2Tag();
            $question2Tag->setDb($this->di->get("dbqb"));
            $question2Tag->question_id = $questionId;
            $question2Tag->tag_name = $tagName;

            $question2Tag->save();
        }

        return true;
    }


    /**
     * Create question
     */
    protected function createQuestion()
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $question->user = $this->form->value("user");
        $question->title = $this->form->value("title");
        $question->text = $this->form->value("text");
        $question->views = 0;
        $question->score = 0;
        $question->entries = 0;
        $question->created = date('Y-m-d H:i:s');

        try {
            $question->save();
        } catch (\Exception $e) {
            $this->form->addOutput("Error:" . $e);
        }
    }

    /**
     * Create question
     */
    protected function createTags()
    {

        try {
        } catch (\Exception $e) {
            $this->form->addOutput("Error:" . $e);
        }
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("questions")->send();
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
