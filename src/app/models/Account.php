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

    /**
     * Account constructor.
     */
    public function __construct()
    {
        $f3 = \Base::instance();
        parent::__construct($f3->get('DB'), self::tableName());
        $this->beforeinsert(array(__CLASS__,'_beforeinsert'));
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'Accounts';
    }

    /**
     * @param $self
     * @param $pkeys
     */
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
        try {
            $this->_transactions = (new Transaction)->findAllByAttribute('accountid', $this->id);
        } catch (\Exception $e) {
            print_r('Sorry this account has no transactions at this time, try making some money');
        }
    }

    /**
     * Updates the amount property.
     *
     * @param $amount
     */
    public function updateAmount($amount)
    {
        $this->amount = $this->amount + ($amount);
        $this->update();
    }
}