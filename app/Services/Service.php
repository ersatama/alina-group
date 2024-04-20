<?php

namespace App\Services;

abstract class Service
{
    protected $model;

    public function update($model, $values)
    {
        foreach ($values as $key => $value) {
            $model->{$key} = $value;
        }
        $model->update();
        return $model;
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function get($where)
    {
        return $this->model::where($where)->get();
    }

    public function firstById($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function deleteById($id): void
    {
        $this->model::where('id', $id)->delete();
    }
}
