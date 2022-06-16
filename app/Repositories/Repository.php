<?php

namespace App\Repositories;

class Repository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->get();
    }

    public function create(array $input)
    {
        return $this->model->create($input);
    }

    public function update($id, array $input)
    {
        $object = $this->model->find($id);
        if ($object == null) return null;

        $object->update($input);

        return $object;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}
