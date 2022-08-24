<?php

namespace App\Repositories\Files;

use App\Enums\FileMimes;
use App\Helpers\DatetimeHelper;
use App\Helpers\FileHelper;
use App\Repositories\Files\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class FileRepository implements FileRepositoryInterface
{

    public function upload(string $path, $file)
    {
        return Storage::disk('local')->put($path, $file);
    }

    public function getUrl(string $file)
    {
        return Storage::disk('local')->url($file);
    }

    public function makeDir(string $dir)
    {
        return Storage::disk('local')->makeDirectory($dir);
    }

    public function isExist(string $path)
    {
        return Storage::disk('local')->exists($path);
    }

    public function getPath($user)
    {
        $guard = current_guard();
        return config('filesystems.file_manager.directory') . '/' . $guard . '/' .
            config('filesystems.file_manager.user_directory_prefix') . $user->id;
    }

    public function getSize(string $path)
    {
        return round(Storage::disk('local')->size($path) / 1024, 1);
    }

    public function getMimeType(string $path)
    {
        return Storage::disk('local')->mimeType($path);
    }

    public function getLastModified(string $path)
    {
        return DatetimeHelper::convertTimestamp(Storage::disk('local')->lastModified($path));
    }

    public function getFiles(string $directory)
    {
        return Storage::disk('local')->files($directory);
    }

    public function getDirectories(string $directory)
    {
        return Storage::disk('local')->directories($directory);
    }

    public function delete($files)
    {
        return Storage::disk('local')->delete($files);
    }

    public function deleteDirectory($directory)
    {
        return Storage::disk('local')->deleteDirectory($directory);
    }

    public function resizeImage(string $file, $width, $height)
    {
        $realPath = FileHelper::realPathStorage($file);
        $mime = Storage::disk('local')->mimeType($file);
        switch ($mime) {
            case FileMimes::MIME_GIF:
                $extension = 'gif';
                break;
            case FileMimes::MIME_PNG:
                $extension = 'png';
                break;
            case FileMimes::MIME_WEBP:
                $extension = 'webp';
                break;
            case FileMimes::MIME_WEBM:
                $extension = 'webm';
                abort(422, 'Cannot resize webm file.');
                break;
            case FileMimes::MIME_JPG:
            default:
                $extension = 'jpg';
                break;
        }
        $name = $file . '_' . $width . 'x' . $height . '.' . $extension;
        $savePath = $realPath . '_' . $width . 'x' . $height . '.' . $extension;
        Image::make($realPath)->resize($width, $height)->save($savePath);
        return $name;
    }
}
