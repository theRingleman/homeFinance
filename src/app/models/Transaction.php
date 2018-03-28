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
        'date' => 'integer',
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

    protected $_account;

    /**
     * Transaction constructor.
     * @param $db
     */
    public function __construct()
    {
        $f3 = \Base::instance();
        parent::__construct($f3->get('DB'), self::tableName());
        $this->beforeinsert(array(__CLASS__,'_beforeinsert'));
        $this->afterinsert(array(__CLASS__,'_afterinsert'));
    }

    /**
     * Transactions database table name.
     *
     * @return string
     */
    public static function tableName()
    {
        return "Transactions";
    }

    /**
     * Gets the transactions account.
     *
     * @return Model
     * @throws \Exception
     */
    public function getAccount()
    {
        return $this->_account;
    }

    /**
     * Sets the transactions account.
     *
     * @throws \Exception
     */
    public function setAccount()
    {
        $this->_account =  (new Account)->findByAttribute('id', $this->accountid);
    }

    /**
     * @param $self
     * @param $pkeys
     */
    static function _beforeinsert($self, $pkeys)
    {
        $self->logged = time();
    }

    static function _afterinsert($self, $pkeys)
    {
        $self->setAccount();
        $account = $self->getAccount();
        $account->updateAmount($self->amount);
    }
}