<?php

namespace Nihl\Forum;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;


/**
 * Controller for forum
 */
class ForumController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var object $forum
     */
    private $forum;
    /**
     * The initialize method sets up forum object which is used to 
     *
     */
    public function initialize()
    {
        // Use to initialise member variables.
        $this->forum = new Forum();
        $this->forum->setdi($this->di);
        $this->forum->setCurrentUser();
    }


    /**
     * Front page of forum
     *
     */
    public function indexActionGet()
    {
        if (!isset($this->forum->currentUser)) {
            $this->di->get("response")->redirect("welcome");
        }

        $nrOfQuestions  = 5;
        $nrOfUsers      = 5;
        $nrOfTags       = 5;

        $page           = $this->di->get("page");
        
        $user           = new \Nihl\User\User();
        $question2tag   = new Question2Tag\Question2Tag();

        $user           ->setDb($this->di->get("dbqb"));
        $question2tag   ->setDb($this->di->get("dbqb"));
        
        
        // Display the latest questions
        $this->forum->getAllQuestions();
        
        $page->add("forum/index/latest-questions", [], "main");
        for($i = 0; $i < $nrOfQuestions; $i++) {
            $currentQuestion    = $this->forum->questions[$i];
            $user               = new \Nihl\User\User();
            $user               ->setDb($this->di->get("dbqb"));
            $user               ->find("username", $currentQuestion->user);
            $questionTags       = $question2tag->findAllWhere("question_id = ?", $currentQuestion->id);

            $page->add("forum/questions/view-question-summary", [
                "question" => $currentQuestion,
                "user" => $user,
                "tags" => $questionTags
            ], "main");
        }

        $tags           = new Tag\Tag();
        $questionTags   = new Question2Tag\Question2Tag();

        $tags           ->setDb($this->di->get("dbqb"));
        $questionTags   ->setDb($this->di->get("dbqb"));
        $page->add("forum/index/most-popular-tags", [], "main");

        foreach($tags->findAll() as $tag) {
            $questions = $questionTags->findAllWhere("tag_name = ?", $tag->name);
            $page->add("forum/tags/view-tag-summary", [
                "tag" => $tag,
                "questions" => count($questions)
            ]);
        }
        // for($i = 0; $i < $nrOfTags; $i++) {
        //     $currentQuestion = $this->forum->questions[$i];
        //     $user->find("username", $currentQuestion->user);
        //     $tags = $question2tag->findAllWhere("question_id = ?", $currentQuestion->id);
        //
        //     $page->add("forum/questions/view-question-summary", [
        //         "question" => $currentQuestion,
        //         "user" => $user,
        //         "tags" => $tags
        //     ], "main");
        // }

        $page->add("forum/index/most-active-users", [], "sidebar-right");

        return $page->render([
            "title" => "All questions",
        ]);
    }



    /**
     * Show all questions
     *
     */
    public function welcomeAction()
    {
        $page = $this->di->get("page");

        $page->add("forum/index/login", [
            "class" => "index-banner"
        ], "flash");

        $page->add("forum/index/index-main", [], "main");

        return $page->render([
            "title" => "All questions",
        ]);
    }


    /**
     * Handler with form to view an item.
     *
     * @param string $questionId    question to view, leave null to see summary of all
     *
     * @return object as a response object
     */
    public function questionsAction($questionId = null) : object
    {
        if (!isset($this->forum->currentUser)) {
            $this->di->get("response")->redirect("welcome");
        }

        $page           = $this->di->get("page");
        $tf             = $this->di->get("textfilter");
        $session        = $this->di->get("session");
        $request        = $this->di->get("request");

        $user           = new \Nihl\User\User();
        $question       = new Question\Question();
        $entries        = new Entry\Entry();
        $comment        = new Comment\Comment();
        $questionTags   = new Question2Tag\Question2Tag();

        $user           ->setDb($this->di->get("dbqb"));
        $question       ->setDb($this->di->get("dbqb"));
        $entries        ->setDb($this->di->get("dbqb"));
        $comment        ->setDb($this->di->get("dbqb"));
        $questionTags   ->setDb($this->di->get("dbqb"));

        $sortBy         = $request->getGet("sortBy", null);

        // Display all questions if $questionid is not provided
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
            $commentForm = new HTMLForm\CreateCommentForm($this->di, $questionId, $this->forum->currentUser->username);
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
                $commentEntryForm = new HTMLForm\CreateCommentForm($this->di, $questionId, $this->forum->currentUser->username, $entry->id);
                $commentEntryForm->check();

                $page->add("forum/questions/create-comment", [
                    "commentForm" => $commentEntryForm->getHTML()
                ]);
            }

        }

        // Display create entry form or user login form depending on if a user is logged in
        if ($this->forum->currentUser) {
            $form = new HTMLForm\CreateEntryForm($this->di, $questionId, $this->forum->currentUser->username);
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


        return $page->render([
            "title" => "View question",
        ]);
    }


    /**
     * Show all tags
     *
     *
     * @return object as a response object
     */
    public function tagsAction() : object
    {
        if (!isset($this->forum->currentUser)) {
            $this->di->get("response")->redirect("welcome");
        }

        $page           = $this->di->get("page");
        $session        = $this->di->get("session");
        $request        = $this->di->get("request");

        $tags           = new Tag\Tag();
        $questionTags   = new Question2Tag\Question2Tag();

        $tags           ->setDb($this->di->get("dbqb"));
        $questionTags   ->setDb($this->di->get("dbqb"));


        $page->add("forum/tags/all-tags-header", []);

        foreach($tags->findAll() as $tag) {
            $questions = $questionTags->findAllWhere("tag_name = ?", $tag->name);
            $page->add("forum/tags/view-tag-summary", [
                "tag" => $tag,
                "questions" => count($questions)
            ]);
        }

        $page->add("forum/tags/all-tags-footer", []);



        return $page->render([
            "title" => "A collection of items",
        ]);
    }

    /**
     * Show selected tag
     *
     * @param string $tagName the tag to view.
     *
     * @return object as a response object
     */
    public function taggedAction($tagName = null) : object
    {
        if (!isset($this->forum->currentUser)) {
            $this->di->get("response")->redirect("welcome");
        }

        $page           = $this->di->get("page");
        $session        = $this->di->get("session");
        $request        = $this->di->get("request");

        $tags           = new Tag\Tag();
        $user           = new \Nihl\User\User();
        $question       = new Question\Question();
        $questionTags   = new Question2Tag\Question2Tag();

        $tags           ->setDb($this->di->get("dbqb"));
        $user           ->setDb($this->di->get("dbqb"));
        $question       ->setDb($this->di->get("dbqb"));
        $questionTags   ->setDb($this->di->get("dbqb"));

        if (!$tagName) {
            $this->di->get("response")->redirect("tags");
        }

        $tags->find("name", $tagName);

        // Find question with $questionId
        if (!$tags->name) {
            $page->add("forum/page-not-found", [
                "message" => "Tag with name " . htmlentities($tagName) . " can't be found"
            ]);

            return $page->render([
                "title" => "Page not found",
            ]);
        }


        $this->forum->getAllQuestionsWithTag($tagName, $sortBy = null);
        $page->add("forum/tags/tag-header", [
            "tag" => $tags
        ]);

        foreach($this->forum->questions as $currentQuestion) {
            $user->find("username", $currentQuestion->user);
            $qTags = $questionTags->findAllWhere("question_id = ?", $currentQuestion->id);

            $page->add("forum/questions/view-question-summary", [
                "question" => $currentQuestion,
                "user" => $user,
                "tags" => $qTags
            ]);
        }
        $page->add("forum/tags/tag-footer", []);
        return $page->render([
            "title" => "A collection of items",
        ]);
    }


    /**
     * Show users
     *
     * @param string $username the user to view.
     *
     * @return object as a response object
     */
    public function usersAction($username = null) : object
    {
        if (!isset($this->forum->currentUser)) {
            $this->di->get("response")->redirect("welcome");
        }

        $page           = $this->di->get("page");
        $session        = $this->di->get("session");
        $request        = $this->di->get("request");

        $user           = new \Nihl\User\User();
        $question       = new Question\Question();
        $question2tag   = new Question2Tag\Question2Tag();
        $entry          = new Entry\Entry();

        $user           ->setDb($this->di->get("dbqb"));
        $question       ->setDb($this->di->get("dbqb"));
        $question2tag   ->setDb($this->di->get("dbqb"));
        $entry          ->setDb($this->di->get("dbqb"));


        if (!$username) {
            $page->add("forum/users/all-users-header", []);

            foreach($user->findAll() as $selectedUser) {
                $questions = $question->findAllWhere("user = ?", $selectedUser->username);
                $page->add("forum/users/view-user-summary", [
                    "user" => $selectedUser,
                    "questions" => count($questions)
                ]);
            }

            $page->add("forum/users/all-users-footer", []);

            return $page->render([
                "title" => "All users",
            ]);
        }

        $user->find("username", $username);

        // Find user with username
        if (!$user->username) {
            $page->add("forum/page-not-found", [
                "message" => "User with name " . htmlentities($username) . " can't be found"
            ]);

            return $page->render([
                "title" => "Page not found",
            ]);
        }

        $editable = false;

        if ($this->forum->getCurrentUser() == $user) {
            $editable = true;
        }
        $page->add("forum/users/view-user", [
            "user" => $user,
            "editable" => $editable
        ]);
        $page->add("forum/users/user-questions-header", ["user" => $user]);
        $this->forum->getAllQuestionsWithUser($user->username);

        foreach($this->forum->questions as $currentQuestion) {
            $question       = new Question\Question();
            $question       ->setDb($this->di->get("dbqb"));
            $qTags = $question2tag->findAllWhere("question_id = ?", $currentQuestion->id);

            $page->add("forum/questions/view-question-summary", [
                "question" => $currentQuestion,
                "user" => $user,
                "tags" => $qTags
            ]);
        }
        $page->add("forum/users/user-entries-header", ["user" => $user]);

        $this->forum->getAllQuestionsUserHasAnswered($user->username);
        foreach($this->forum->questions as $currentQuestion) {
            $question       = new Question\Question();
            $user           = new \Nihl\User\User();

            $question       ->setDb($this->di->get("dbqb"));
            $user           ->setDb($this->di->get("dbqb"));
            $user->find("username", $currentQuestion->user);

            $qTags = $question2tag->findAllWhere("question_id = ?", $currentQuestion->id);

            $page->add("forum/questions/view-question-summary", [
                "question" => $currentQuestion,
                "user" => $user,
                "tags" => $qTags
            ]);
        }
        $page->add("forum/users/user-footer", []);
        return $page->render([
            "title" => "A collection of items",
        ]);
    }

    /**
     *
     */
    public function editUserAction()
    {
        $page           = $this->di->get("page");

        $page->add("forum/users/edit", []);

        return $page->render([
            "title" => "A collection of items",
        ]);
    }

    /**
     * Show all questions
     *
     * @return object as a response object
     */
    public function catchAll(...$args)
    {
        $page = $this->di->get("page");
        $page->add("forum/page-not-found", []);
        var_dump($args);
        return $page->render([
            "title" => "Page not found",
        ]);
    }
}
