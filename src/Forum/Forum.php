<?php

namespace Nihl\Forum;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A series of functions used in the forum
 */
class Forum implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Variables
     */
    public $currentUser;
    public $questions;

    /**
     * Set currentUser to the user logged in
     */
    public function setCurrentUser()
    {
        $session = $this->di->get("session");

        if ($session->get("loggedin")) {
            $user = new \Nihl\User\User();
            $user->setDb($this->di->get("dbqb"));

            $user->find("username", $session->get("loggedin"));

            $this->currentUser = $user;
        }
    }
    /**
     * get currentUser
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }


    /**
     * Get all questions from question database
     *
     * @param $sortBy   Optional, sort array
     */
    public function getAllQuestions($sortBy = null)
    {
        $question = new Question\Question();
        $question->setDb($this->di->get("dbqb"));
        $this->questions = $question->findAll();
        $this->sortQuestions($sortBy);
    }



    /**
     * Get all questions from question database with tagname
     *
     * @param $tagname  Name of tag
     * @param $sortBy   Optional, sort array
     */
    public function getAllQuestionsWithTag($tagname, $sortBy = null)
    {
        // $question       = new Question\Question();
        // $question->setDb($this->di->get("dbqb"));
        $question2Tag   = new Question2Tag\Question2Tag();
        $question2Tag   ->setDb($this->di->get("dbqb"));



        $questionsWithTag = $question2Tag->findAllWhere("tag_name = ?", $tagname);

        $taggedQuestions = [];

        foreach($questionsWithTag as $q2t) {
            $id = $q2t->question_id;
            $question       = new Question\Question();
            $question       ->setDb($this->di->get("dbqb"));

            $q = $question->findById($id);
            array_push($taggedQuestions, $q);
        }

        $this->questions = $taggedQuestions;
        $this->sortQuestions($sortBy);
    }



    /**
     * Get all questions from question database with username
     *
     * @param $username     Username
     * @param $sortBy       Optional, sort array
     */
    public function getAllQuestionsWithUser($username, $sortBy = null)
    {
        $question       = new Question\Question();
        $question       ->setDb($this->di->get("dbqb"));

        $this->questions = $question->findAllWhere("user = ?", $username);

        $this->sortQuestions($sortBy);
    }


    /**
     * Get all questions from question database that user has answered
     *
     * @param $username     Username
     * @param $sortBy       Optional, sort array
     */
    public function getAllQuestionsUserHasAnswered($username, $sortBy = null)
    {
        $entry          = new Entry\Entry();
        $entry          ->setDb($this->di->get("dbqb"));

        $userEntries = $entry->findAllWhere("user = ?", $username);

        $userAnsweredQuestions = [];
        foreach($userEntries as $userEntry) {
            $id = $userEntry->question;
            $question       = new Question\Question();
            $question       ->setDb($this->di->get("dbqb"));

            $q = $question->findById($id);
            array_push($userAnsweredQuestions, $q);
        }
        $this->questions = $userAnsweredQuestions;
        $this->sortQuestions($sortBy);
    }

    /**
     * Sort questionsarray
     *
     * @param string $sortBy How to sort array
     */
    protected function sortQuestions($sortBy = null)
    {
        switch ($sortBy){
            case "views":
                $this->sortByValue("views");
                break;
            case "answers":
                $this->sortByValue("entries");
                break;
            case "votes":
                $this->sortByValue("score");
                break;
            case "oldest":
                ksort($this->questions);
                break;
            case "newest":
            default:
                krsort($this->questions);
                break;
        }
    }

    /**
     * Sort questions array by values
     *
     * @param string $value Value to sort by
     */
    public function sortByValue($value)
    {
        usort($this->questions, fn($q1, $q2) => $q2->$value <=> $q1->$value);
    }
}
