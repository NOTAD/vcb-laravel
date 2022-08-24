<?php

namespace App\Services;

use App\Repositories\Files\Interfaces\FileRepositoryInterface;
use Illuminate\Http\Request;


class FileService
{

    private $repository;

    public function __construct(FileRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function upload(Request $request, $user)
    {
        $this->getDirectorySize($user);
        $data = [];
        $file = $request->file('upload');
        if ($file->isValid()) {
            $rootDirectory = $this->repository->getPath($user);
            if (strpos($request->input('directory'), $rootDirectory) !== false) {
                $path = $this->repository->upload($request->input('directory'), $file);
                $url = $this->repository->getUrl($path);
                $name = $file->hashName();
                $data = ($request->has('editor'))
                    ? [
                        'uploaded' => 1,
                        'fileName' => $name,
                        'url' => $url,
                    ]
                    : [
                        'name' => $name,
                        'url' => $url,
                        'path' => $path,
                        'size' => $this->repository->getSize($path),
                        'last_modified' => $this->repository->getLastModified($path),
                        'is_selected' => false,

                    ];
            } else {
                abort(422, 'Destination directory wrong.');
            }

        } else {
            abort(422, 'File invaild.');
        }
        return $data;

    }

    public function getDirectorySize($user)
    {
        $directory = $this->repository->getPath($user);
        $files = $this->repository->getFiles($directory);
        $size = 0;
        foreach ($files as $file) {
            $size += $this->repository->getSize($file);
        }
        return $size;
    }

    public function resizeImage($files, $width, $height, $user)
    {
        $directory = $this->repository->getPath($user);
        $files = array_map(function ($file) use ($width, $height, $directory) {
            $path = $this->repository->resizeImage($file['path'], $width, $height);
            return [
                'name' => str_replace($directory . '/', '', $path),
                'url' => $this->repository->getUrl($path),
                'path' => $path,
                'size' => $this->repository->getSize($path),
                'mime' => $this->repository->getMimeType($path),
                'last_modified' => $this->repository->getLastModified($path),
                'is_selected' => false,
            ];
        }, $files);
        return $files;
    }

    public function delete($files, $directories)
    {
        $files = array_map(function ($file) {
            return $file['path'];
        }, $files);

        if (!empty($files)) {
            $this->repository->delete($files);
        }

        foreach ($directories as $directory) {
            $this->repository->deleteDirectory($directory['path']);
        }
        return true;
    }

    public function makeDirectory($params, $user)
    {
        $rootDirectory = $this->repository->getPath($user);

        if (strpos($params['directory'], $rootDirectory) !== false) {
            $directory = $params['directory'] . '/' . $params['name'];
            $this->repository->makeDir($directory);

            return [
                'name' => $params['name'],
                'path' => $directory,
            ];
        }

        return abort(403);

    }

    public function browser($user, $directory = '')
    {
        if (empty($directory)) {
            $directory = $this->repository->getPath($user);
        }

        $directories = array_map(function ($path) {
            $separates = explode('/', $path);
            return [
                'name' => end($separates),
                'path' => $path
            ];

        }, $this->repository->getDirectories($directory));

        $files = array_map(function ($file) use ($directory) {
            return [
                'name' => str_replace($directory . '/', '', $file),
                'url' => $this->repository->getUrl($file),
                'path' => $file,
                'size' => $this->repository->getSize($file),
                'mime' => $this->repository->getMimeType($file),
                'last_modified' => $this->repository->getLastModified($file),
                'is_selected' => false,
            ];
        }, $this->repository->getFiles($directory));

        return [
            'directory' => $directory,
            'directories' => $directories,
            'files' => $files,
        ];

    }
}
