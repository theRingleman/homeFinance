<?php

namespace models;

class Account extends Model
{
    public $attributes = [
        'id',
        'accountnumber',
        'type',
        'amount',
        'created'
    ];

    public $validationRules = [
        'created' => 'date',
        'accountnumber' => 'required|integer',
        'type' => 'required|alpha',
        'amount' => 'required|numeric'
    ];

    public $filterRules = [
        'created' => 'trim|sanitize_numbers',
        'acountnumber' => 'trim|sanitize_numbers',
        'type' => 'trim|sanitize_string',
        'amount' => 'trim|sanitize_floats'
    ];

    public function __construct($db)
    {
        parent::__construct($db, self::tableName());
    }

    public static function tableName()
    {
        return 'Accounts';
    }
}