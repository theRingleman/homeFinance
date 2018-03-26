<?php

namespace controllers;

use models\Transaction;

class TransactionsController extends Controller
{
    /**
     * Shows all transactions.
     * @TODO This will need to be locked down to admins, or just to parents.
     */
    public function index()
    {
        $transactions = (new Transaction)->all();
        $this->renderJson(array_map(function ($transaction) {
            return $transaction->toEndPoint();
        }, $transactions));
    }

    /**
     * @throws \Exception
     */
    public function show()
    {
        $transaction = (new Transaction)->findByAttribute('id', $this->params['id']);
        $this->renderJson($transaction->toEndPoint());
    }

    /**
     * Upadtes a transaction.
     * @TODO I need a way to specify when it is an update.
     * @throws \Exception
     */
    public function update()
    {
        $transaction = (new Transaction)->findByAttribute('id', $this->params['id']);
        if ($transaction->edit($this->attributes)) {
            $this->renderJson($transaction->toEndPoint());
        } else {
            $this->renderJson([
                'message' => "Something went horribly wrong...",
                'errors' => $transaction->errors
            ]);
        }
    }

    /**
     * Creates an account.
     * @throws \Exception
     */
    public function create()
    {
        $transaction = new Transaction;
        if (!empty($this->attributes)) {
            if ($transaction->create($this->attributes)) {
                $this->renderJson([
                    'message' => 'Transaction created successfully',
                    'account' => $transaction->toEndPoint()
                ]);
            } else {
                $this->renderJson([
                    "message" => "Something went horribly wrong",
                    "errors" => $transaction->errors
                ]);
            }
        } else {
            throw new \Exception("You are missing the required parameters", 401);
        }
    }

    /**
     * Deletes a transaction, then double checks to make sure said transaction was deleted.
     *
     * @throws \Exception
     */
    public function delete()
    {
        (new Transaction)
            ->findByAttribute('id', $this->params['id'])
            ->delete();

        try {
            (new Transaction)->findByAttribute('id', $this->params['id']);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Not found.') {
                $this->renderJson(['message' => 'Transaction deleted successfully']);
            } else {
                $this->renderJson(['message' => 'Something went horribly wrong...']);
            }
        }
    }
}