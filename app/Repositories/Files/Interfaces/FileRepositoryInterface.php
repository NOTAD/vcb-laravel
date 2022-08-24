<?php

namespace App\Repositories\Files\Interfaces;


interface FileRepositoryInterface
{
    public function upload(string $path, $file);

    public function getUrl(string $file);

    public function makeDir(string $dir);

    public function isExist(string $path);

    public function getPath($user);

    public function getFiles(string $directory);

    public function delete(array $files);

    public function deleteDirectory(string $directory);

    public function getSize(string $path);

    public function getLastModified(string $path);

    public function resizeImage(string $file, $width, $height);

    public function getMimeType(string $path);

}
