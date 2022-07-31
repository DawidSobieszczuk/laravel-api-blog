<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository extends Repository
{
    public function __construct(File $file)
    {
        $this->model = $file;
    }

    public function findByName(string $name): File
    {
        return $this->model->where('name', $name)->first();
    }
}
