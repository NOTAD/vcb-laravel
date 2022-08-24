<?php

namespace App\Http\Middleware;

use App\Services\FileService;
use Closure;
use Illuminate\Http\Request;

class FilePermission
{
    /**
     * @var FileService
     */
    protected $_fileService;

    public function __construct(FileService $fileService)
    {
        $this->_fileService = $fileService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = current_guard() === 'admin' ? admin() : user();
        if ($this->_fileService->getDirectorySize($user) >= config('filesystems.file_manager.user_storage_limit')) {
            return response()->json([
                'error' => 'Dung lượng thư viện ảnh đã đầy.'
            ], 403);
        }
        return $next($request);
    }
}
