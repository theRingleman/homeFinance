<?php

namespace controllers;

use models\Account;

class AccountsController extends Controller
{

    /**
     *
     */
    public function index()
    {
        $accounts = (new Account)->all();
        $this->renderJson(array_map(function ($account) {
            return $account->toEndPoint();
        }, $accounts));
    }

    /**
     * @throws \Exception
     */
    public function show()
    {
        $account = (new Account)->findByAttribute('id', $this->params['id']);
        $this->renderJson($account->toEndPoint());
    }

    /**
     * @throws \Exception
     */
    public function create()
    {
        $account = new Account();
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