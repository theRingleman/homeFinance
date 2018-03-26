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
        $f3 = \Base::instance();
        parent::__construct($f3->get('DB'), self::tableName());
    }

    public static function tableName()
    {
        return 'Stores';
    }
}