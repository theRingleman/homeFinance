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
     * Use this method when we are looking for a single model.
     *
     * @param $attribute
     * @param $value
     * @return Model
     * @throws \Exception
     */
    public function findByAttribute($attribute, $value)
    {
        $this->load(["{$attribute}=?", $value]);
        if ($this->dry()) {
            throw new \Exception("Not found.", 404);
        } else {
            return $this;
        }
    }

    /**
     * This is for when we need to find multiple items instead of one. Returns an array of models that match the query.
     *
     * @param $attribute
     * @param $value
     * @return array
     * @throws \Exception
     */
    public function findAllByAttribute($attribute, $value)
    {
        $query = $this->find(["{$attribute}=?" => $value]);
        if (empty($query)) {
            throw new \Exception('Not found.', 404);
        } else {
            return $query;
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
     * @param $values
     * @param bool $runValidation We give the option of skipping validation, otherwise lets always run it.
     * @return bool
     * @throws \Exception
     */
    public function edit($values, $runValidation = true)
    {
        if ($runValidation) {
            $validated = $this->validate($values);
            if (!is_array($validated)) {
                return $this->_edit($values);
            } else {
                $this->errors = $validated;
                return false;
            }
        } else {
            return $this->_edit($values);
        }
    }

    /**
     * Internal edit so I dont have to repeat myself.
     * @param $values
     * @return bool
     */
    private function _edit($values)
    {
        $this->copyFrom($values);
        $this->update();
        return true;
    }

    /**
     * @param $values
     * @return bool
     * @throws \Exception
     */
    public function create($values, $runValidation = true)
    {
        if ($runValidation) {
            $validated = $this->validate($values);
            if (!is_array($validated)) {
                $this->copyFrom($values);
                $this->save();
                return true;
            } else {
                $this->errors = $validated;
                return false;
            }
        } else {
            $this->copyfrom($values);
            $this->save();
        }
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

    /**
     * Deletes a model from the database.
     */
    public function delete()
    {
        $this->erase();
    }
}