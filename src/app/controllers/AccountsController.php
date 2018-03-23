<?php

namespace controllers;

use models\Account;

class AccountsController extends Controller
{

    public function index()
    {
        echo 'you have made it to the accounts controller';
    }

    public function create()
    {
        $account = new Account($this->db);
        if (!empty($this->attributes)) {
            $account->create($this->attributes);
        } else {
            throw new \Exception("You are missing the required parameters", 401);
        }
    }

}