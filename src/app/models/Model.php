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
    public function toEndPoint()
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[$attribute] = $this->{$attribute};
        }
        return $attributes;
    }

    /**
     * @param $attribute
     * @param $value
     * @throws \Exception
     */
    public function findByAttribute($attribute, $value)
    {
        $this->load(["{$attribute}=?", $value]);
        if ($this->dry()) {
            throw new \Exception("Not found.", 404);
        }
    }

    /**
     * @return void
     */
    public function all()
    {
        $this->load();
    }

    /**
     * @param $id
     * @param $values
     * @param bool $runValidation We give the option of skipping validation, otherwise lets always run it.
     * @throws \Exception
     */
    public function edit($id, $values, $runValidation = true)
    {
        $this->load(['id=?', $id]);
        if ($runValidation) {
            if ($this->validate($values)) {
                $this->_edit($values);
            }
        } else {
            $this->_edit($values);
        }
    }

    /**
     * Internal edit so I dont have to repeat myself.
     * @param $values
     */
    private function _edit($values) {
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
     * @TODO I am thinking that we may want to throw an error if this fails.
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