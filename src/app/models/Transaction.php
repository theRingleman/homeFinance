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
        'amount' => 'required|numeric'
    ];

    public $filterRules = [
        'logged' => 'trim|sanitize_numbers',
        'date' => 'trim|sanitize_numbers',
        'storeid' => 'trim|sanitize_numbers',
        'accountid' => 'trim|sanitize_numbers',
        'amount' => 'trim|sanitize_floats'
    ];

    protected $_account;

    /**
     * Transaction constructor.
     * @throws \Exception
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
     * @return Account
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
        $self->type = $self->amount >= 0 ? "Credit" : "Debit";
    }

    /**
     * @param $self Transaction
     * @param $pkeys
     * @throws \Exception
     */
    static function _afterinsert($self, $pkeys)
    {
        $self->setAccount();
        $self->getAccount()->updateAmount($self->amount);
    }

    public function updateAmount($newAmount)
    {
        $amount = $newAmount - $this->amount;
        $this->setAccount();
        $this->getAccount()->updateAmount($amount);
    }
}