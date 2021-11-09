<?php

namespace Nihl\Forum\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Nihl\Forum\Question\HTMLForm\CreateForm;
use Nihl\Forum\Question\HTMLForm\EditForm;
use Nihl\Forum\Question\HTMLForm\DeleteForm;
use Nihl\Forum\Question\HTMLForm\UpdateForm;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class QuestionController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */
    //private $data;



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        $this->forum = new \Nihl\Forum\Forum();
        $this->forum->setdi($this->di);
        $this->forum->setCurrentUser();
    }



    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $page->add("question/crud/view-all", [
            "items" => $question->findAll(),
        ]);

        return $page->render([
            "title" => "A collection of items",
        ]);
    }




    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");

        if ($session->get("loggedin")) {
            $userLogin = new \Nihl\User\User();
            $userLogin->setDb($this->di->get("dbqb"));

            $currentUser = $userLogin->find("username", $session->get("loggedin"));

            $form = new HTMLForm\CreateQuestionForm($this->di, $currentUser->username);
            $form->check();

            $page->add("question/crud/create", [
                "form" => $form->getHTML(),
            ]);
        } else {
            $form = new \Nihl\User\HTMLForm\UserLoginForm($this->di);
            $form->check();

            $page->add("anax/v2/article/default", [
                "content" => $form->getHTML(),
            ]);
        }

        return $page->render([
            "title" => "Create a item",
        ]);
    }

    /**
     * Handler with form to view an item.
     *
     * @param int $id the id to view.
     *
     * @return object as a response object
     */
    public function viewAction(int $questionId) : object
    {
        $page           = $this->di->get("page");
        $tf             = $this->di->get("textfilter");
        $session        = $this->di->get("session");
        $request        = $this->di->get("request");
        $user           = new \Nihl\User\User();
        $question       = new Question();
        $entries        = new \Nihl\Forum\Entry\Entry();
        $comment        = new \Nihl\Forum\Comment\Comment();
        $questionTags   = new \Nihl\Forum\Question2Tag\Question2Tag();

        $user           ->setDb($this->di->get("dbqb"));
        $question       ->setDb($this->di->get("dbqb"));
        $entries        ->setDb($this->di->get("dbqb"));
        $comment        ->setDb($this->di->get("dbqb"));
        $questionTags   ->setDb($this->di->get("dbqb"));
        if (!$questionId) {
            $this->forum->getAllQuestions($sortBy);

            $page->add("forum/questions/questions-header", [], "main");

            foreach($this->forum->questions as $currentQuestion) {
                $user           = new \Nihl\User\User();
                $user           ->setDb($this->di->get("dbqb"));
                $user->find("username", $currentQuestion->user);
                $tags = $questionTags->findAllWhere("question_id = ?", $currentQuestion->id);

                $page->add("forum/questions/view-question-summary", [
                    "question" => $currentQuestion,
                    "user" => $user,
                    "tags" => $tags
                ], "main");
            }

            $page->add("forum/questions/questions-footer", [], "main");

            return $page->render([
                "title" => "All questions",
            ]);
        }

        // Find question with $questionId;
        $question->findById($questionId);

        // Find question with $questionId
        if (!$question->id) {
            $page->add("forum/page-not-found", [
                "message" => "Question with id " . htmlentities($questionId) . " can't be found"
            ]);

            return $page->render([
                "title" => "Page not found",
            ]);
        }

        // Parse question text with markdown
        $question->incrementViews();
        $question->save();
        $questionText = $tf->parse($question->text, ["markdown"])->text;
        
        $user->find("username", $question->user);
        
        $comment = $comment->findAllWhere("question = ?", $questionId);
        $tags = $questionTags->findAllWhere("question_id = ?", $questionId);
        $page->add("forum/questions/view-question", [
            "question" => $question,
            "questionText" => $questionText,
            "user" => $user,
            "comments" => $comment,
            "tags" => $tags
        ]);

        if ($this->forum->currentUser) {
            $commentForm = new \Nihl\Forum\HTMLForm\CreateCommentForm($this->di, $questionId, $this->forum->currentUser->username);
            $commentForm->check();

            $page->add("forum/questions/create-comment", [
                "commentForm" => $commentForm->getHTML()
            ]);
        }

        // Add views for entries
        $entries = $entries->findAllWhere("question = ?", $questionId);

        $page->add("forum/questions/answers-header", [
            "entries" => count($entries)
        ]);

        foreach($entries as $entry) {
            $user           = new \Nihl\User\User();
            $user           ->setDb($this->di->get("dbqb"));
            $user->find("username", $entry->user);

            $entryText = $tf->parse($entry->text, ["markdown"])->text;
            $page->add("forum/questions/view-entry", [
                "entry" => $entry,
                "entryText" => $entryText,
                "user" => $user,
                "comments" => $comment,
            ]);

            if ($this->forum->currentUser) {
                $commentEntryForm = new \Nihl\Forum\HTMLForm\CreateCommentForm($this->di, $questionId, $this->forum->currentUser->username, $entry->id);
                $commentEntryForm->check();

                $page->add("forum/questions/create-comment", [
                    "commentForm" => $commentEntryForm->getHTML()
                ]);
            }

        }

        // Display create entry form or user login form depending on if a user is logged in
        if ($this->forum->currentUser) {
            $form = new \Nihl\Forum\HTMLForm\CreateEntryForm($this->di, $questionId, $this->forum->currentUser->username);
            $form->check();

            $page->add("entry/crud/create", [
                "form" => $form->getHTML(),
            ]);
        } else {
            $form = new \Nihl\User\HTMLForm\UserLoginForm($this->di);
            $form->check();

            $page->add("anax/v2/article/default", [
                "content" => $form->getHTML(),
            ]);
        }

    }

    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        $page = $this->di->get("page");
        $form = new DeleteForm($this->di);
        $form->check();

        $page->add("question/crud/delete", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Delete an item",
        ]);
    }



    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("question/crud/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Update an item",
        ]);
    }
}
