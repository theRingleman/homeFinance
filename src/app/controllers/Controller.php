<?php

namespace controllers;

class Controller {

	/**
	 * @param $db DB FatFree database object for easy access.
	 */
	public $db;

	/**
	 * @param $f3 \Base FatFree instance for easy access.
	 */
	public $f3;

	/**
	 * @param $params array route parameters.
	 */
	public $params;
	/**
	 * @param $attributes \stdClass JSON attributes passed to route.
	 */
	public $attributes;
	/**
	 * @param $errors array Errors passed when needed for listing errors.
	 */
	public $errors;

	/**
	 * @param $allowedRoutes array Add any routes in this array that you would like to allow without
     * authenticating.
	 */
	protected $allowedRoutes = [
    ];

    /**
     * Controller constructor.
     * @param $f3 \Base
     */
    public function __construct($f3)
	{
		$this->f3 = $f3;
		$this->db = $f3->get("DB");
		$this->auth = $f3->get('AUTH');
	}

    /**
     * Handles anything you want done before routing, in this case auth and converting the request body to a
     * JSON decoded object.
     */
    public function beforeroute()
	{
        $this->params = $this->f3->get('PARAMS');
        if ($this->f3->exists('BODY')) {
            $this->attributes = json_decode($this->f3->get('BODY'));
        }
	}

    /**
     * Handles anything you want done after routing.
     */
    public function afterroute()
	{
		
	}

    /**
     * Primarily for model errors, when they dont validate.
     * @TODO Again update when we go nuts on error handling.
     * @param $errors
     */
    public function throwError($errors)
	{
		$this->f3->set("MODELERRORS", $errors);
		$this->f3->error(404, 'Sorry but some information could not be validated');
	}

    /**
     * Returns errors in JSON format.
     * @TODO I need to update this so I can render all errors.
     */
    public function renderError()
	{
		$message = [
			"message" => $this->f3->get('ERROR.text'),
			"errors" => $this->f3->get("MODELERRORS")
		];
		$this->f3->set('response', $message);
		echo \Template::instance()->render('json.php');
	}

    /**
     * Sends our response to the json view which converts our response to json.
     *
     * @param $response array
     */
    public function renderJson(array $response)
	{
		$this->f3->set('response', $response);
		echo \Template::instance()->render('json.php');
	}

    /**
     * Gets our token from the request headers.
     *
     * @return null|string
     */
    private function getToken()
    {
        if (is_null($header = $this->f3->get("HEADERS")['Authorization'])) {
            return null;
        } else {
            preg_match('/Bearer\s(\S+)/', $header, $matches);
            return $matches[1];
        }

    }

    /**
     * We want to allow certain routes to bypass auth, you have to add the route you want to bypass in the
     * @link $allowedRoutes array.
     *
     * @return bool
     */
    private function checkRoute()
    {
        return in_array($this->params[0], $this->allowedRoutes);
    }

    /**
     * Implement your validation method here
     */
    private function validate()
    {
        return true;
    }
}