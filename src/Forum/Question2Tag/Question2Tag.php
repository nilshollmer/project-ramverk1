<?php

namespace Nihl\Forum\Question2Tag;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Question2Tag extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Question2Tag";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $question_id;
    public $tag_name;
}
