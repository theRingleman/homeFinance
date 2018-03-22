<?php

namespace models;

class Transaction extends Model
{

    public $attributes = [
        'id',
        'logged',
        'date',
        'storeid',
        'accountid',
        'type',
        'amount'
    ];

    public $validationRules = [
        'logged' => 'date',
        'date' => 'date',
        'storeid' => 'integer',
        'accountid' => 'required|integer',
        'type' => 'required|alpha',
        'amount' => 'required|numeric'
    ];

    public $filterRules = [
        'logged' => 'trim|sanitize_numbers',
        'date' => 'trim|sanitize_numbers',
        'storeid' => 'trim|sanitize_numbers',
        'accountid' => 'trim|sanitize_numbers',
        'type' => 'trim|sanitize_string',
        'amount' => 'trim|sanitize_floats'
    ];

    public function __construct($db)
    {
        parent::__construct($db, self::tableName());
    }

    public static function tableName()
    {
        return "Transactions";
    }

}