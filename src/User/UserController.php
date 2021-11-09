<?php

namespace Nihl\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Nihl\User\HTMLForm\UserLoginForm;
use Nihl\User\HTMLForm\CreateUserForm;
use Nihl\User\HTMLForm\UpdateUserForm;

use Anax\Route\Exception\ForbiddenException;
use Anax\Route\Exception\NotFoundException;
use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
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
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $this->di->get("response")->redirect("user/login");
    }



    /**
     * Login/Logout page
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $loggedIn = $this->di->get("session")->get("loggedin", null);

        if ($loggedIn) {
            $form = new HTMLForm\UserLogoutForm($this->di);
            $form->check();
            $page->add("anax/v2/article/default", [
                "content" => $form->getHTML(),
            ]);
        } else {
            $form = new HTMLForm\UserLoginForm($this->di);
            $form->check();

            $page->add("anax/v2/article/default", [
                "content" => $form->getHTML(),
            ]);
        }
        return $page->render([
            "title" => "A login page",
        ]);
    }



    /**
     * Create user page
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $loggedIn = $this->di->get("session")->get("loggedin", null);

        if ($loggedIn) {
            $this->di->get("response")->redirect("user/login");
        }
        
        $form = new CreateUserForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "A create user page",
        ]);
    }

    /**
     * Handler with form to update a user.
     *
     * @param int $id the id to update.
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function updateAction(int $userid = null) : object
    {
        $page = $this->di->get("page");
        $form = new UpdateUserForm($this->di, $userid);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "A create user page",
        ]);
    }
}
