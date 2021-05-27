<?php

namespace Nihl\Forum\Question;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Question extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Question";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user;
    public $title;
    public $text;
    public $views;
    public $score;
    public $entries;
    public $created;
    public $updated;
    public $deleted;

    /**
     * Get id from title
     *
     *
     * @return integer
     */
    public function getIdFromTitleAndUsername($title, $user)
    {
        $this->findWhere("title = ? AND user = ?", [$title, $user]);
        return $this->id;
    }

    /**
     * Increment the number of views of question
     *
     *
     * @return void
     */
    public function incrementViews()
    {
        $this->views++;
    }

    /**
     * Increment the questions score
     *
     *
     * @return void
     */
    public function incrementScore()
    {
        $this->score++;
    }

    /**
     * Decrement the questions score
     *
     *
     * @return void
     */
    public function decrementScore()
    {
        $this->score--;
    }

    /**
     * Increment the questions entries
     *
     *
     * @return void
     */
    public function incrementEntries()
    {
        $this->entries++;
    }

    /**
     * Decrement the questions entries
     *
     *
     * @return void
     */
    public function decrementEntries()
    {
        $this->entries--;
    }

}
