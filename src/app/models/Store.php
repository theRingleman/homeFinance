<?php

namespace models;

class Store extends Model
{
    public $attributes = [
        'id',
        'name'
    ];

    public $validationRules = [
        'name' => 'required|alpha',
    ];

    public $filterRules = [
        'name' => 'trim|sanitize_string',
    ];

    public function __construct($db)
    {
        parent::__construct($db, self::tableName());
    }

    public static function tableName()
    {
        return 'Stores';
    }
}