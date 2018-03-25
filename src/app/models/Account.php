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
        'accountnumber' => 'integer',
        'type' => 'required|alpha',
        'amount' => 'required|numeric'
    ];

    public $filterRules = [
        'acountnumber' => 'trim|sanitize_numbers',
        'type' => 'trim|sanitize_string',
        'amount' => 'trim|sanitize_floats'
    ];

    public function __construct($db)
    {
        parent::__construct($db, self::tableName());
        $this->beforeinsert(array(__CLASS__,'_beforeinsert'));
    }

    public static function tableName()
    {
        return 'Accounts';
    }

    static function _beforeinsert($self, $pkeys)
    {
        $self->created = time();
    }
}