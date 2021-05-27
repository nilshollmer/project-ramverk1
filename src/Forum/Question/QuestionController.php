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



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     ;
    // }



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
    public function viewAction(int $id) : object
    {
        $page = $this->di->get("page");
        $question = new Question();
        $entries = new \Nihl\Forum\Entry\Entry();
        $question->setDb($this->di->get("dbqb"));
        $entries->setDb($this->di->get("dbqb"));

        $page->add("question/crud/view-question", [
            "question" => $question->findById($id),
            "entries" => $entries->findAllWhere("question = ?", $id),
        ]);

        return $page->render([
            "title" => "View question",
        ]);
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
