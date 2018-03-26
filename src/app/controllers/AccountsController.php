<?php

namespace controllers;

use models\Account;

class AccountsController extends Controller
{
    /**
     * Shows all accounts.
     * @TODO This will need to be locked down to admins, or just to parents.
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
     * Upadtes an account.
     * @TODO I need a way to specify when it is an update.
     * @throws \Exception
     */
    public function update()
    {
        $account = (new Account)->findByAttribute('id', $this->params['id']);
        if ($account->edit($this->attributes)) {
            $this->renderJson($account->toEndPoint());
        } else {
            $this->renderJson([
                'message' => "Something went horribly wrong...",
                'errors' => $account->errors
            ]);
        }
    }

    /**
     * Creates an account.
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

    /**
     * @throws \Exception
     * @TODO We need a way to verify that an item was deleted successfully.
     */
    public function delete()
    {
        (new Account)
            ->findByAttribute('id', $this->params['id'])
            ->delete();

        try {
            (new Account)->findByAttribute('id', $this->params['id']);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Not found.') {
                $this->renderJson(['message' => 'Account deleted successfully']);
            } else {
                $this->renderJson(['message' => 'Something went horribly wrong...']);
            }
        }
    }

    public function test()
    {
        $account = (new Account)->findByAttribute('id', 12);
        $account->setTransactions();
        print_r($account->getTransactions());
    }
}