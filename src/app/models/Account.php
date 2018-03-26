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

    protected $_transactions = [];

    public function __construct()
    {
        $f3 = \Base::instance();
        parent::__construct($f3->get('DB'), self::tableName());
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

    /**
     * @return array
     */
    public function getTransactions()
    {
        return $this->_transactions;
    }

    /**
     * Sets the transactions based on the id.
     */
    public function setTransactions()
    {
        $this->_transactions = (new Transaction)->find(['accountid = ?', $this->id]);
    }
}