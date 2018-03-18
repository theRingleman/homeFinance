<?php

namespace models;

use DB\SQL\Mapper;

class Model extends Mapper
{
	public $attributes = [];
	public $validationRules;
	public $filterRules;
	public $errors;

    /**
     * @return array
     */
    public function toEndPoint(){
		$attributes = [];
		foreach ($this->attributes as $attribute) {
			$attributes[$attribute] = $this->{$attribute};
		}
		return $attributes;
	}

    /**
     * @param $attribute
     * @param $value
     * @return array
     * @throws \Exception
     */
    public function findByAttribute($attribute, $value){
		$this->load(["{$attribute}=?", $value]);
		if ($this->dry()) {
		    throw new \Exception("Not found.", 404);
        } else {
            return $this->query;
        }
	}

    /**
     * @return array
     */
    public function all()
	{
		$this->load();
		return $this->query;
	}

    /**
     * @param $id
     * @param $values
     */
    public function edit($id, $values)
	{
		$this->load(['id=?', $id]);
		$this->copyFrom($values);
		$this->update();
	}

    /**
     * @param $values
     */
    public function create($values)
	{
		$this->copyFrom($values);
		$this->save();	
	}

    /**
     * @param $values
     * @return array|bool|null
     * @throws \Exception
     */
    public function validate($values)
	{
		$validator = new \GUMP;
		$values = $validator->sanitize((array)$values);
		$validator->validation_rules($this->validationRules);
		$validator->filter_rules($this->filterRules);

		$validatedData = $validator->run($values);

        return $validatedData === false ? $validator->get_errors_array() : true;
	}

	public function delete($id)
	{
		$this->load(['id' => $id]);
		$this->erase();
	}
}