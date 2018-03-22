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

    public $validationRules = [];

    public $filterRules = [];

}