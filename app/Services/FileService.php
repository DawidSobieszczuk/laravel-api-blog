<?php

namespace App\Services;

use App\Repositories\FileRepository;
use Illuminate\Http\Request;

class FileService extends BaseService
{
    protected $uploadRules = [
        'file' => 'required',
    ];

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByName($name)
    {
        return $this->repository->findByName($name);
    }

    public function uploadFromRequest(Request $request)
    {
        $request->validate($this->uploadRules);

        $file = $request->file('file');
        $input = [
            'name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'path' => str_replace('public', 'storage', $file->store('public/files')), // TODO: rewrite file system
        ];

        return $this->repository->create($input);
    }
}
