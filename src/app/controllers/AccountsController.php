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
            if ($account->create($this->attributes)) {
                $this->renderJson([
                    'message' => 'Account created successfully',
                    'account' => $account->toEndPoint()
                ]);
            } else {
                $this->renderJson([
                    "message" => "Something went horribly wrong",
                    "errors" => $account->errors
                ]);
            }
        } else {
            throw new \Exception("You are missing the required parameters", 401);
        }
    }

}