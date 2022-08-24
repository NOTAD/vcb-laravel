<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\ResizeImageRequest;
use App\Http\Requests\File\UploadFileRequest;
use App\Services\FileService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $hasEditor = $request->has('CKEditor');
        $hasSelector = $request->has('selector');
        $layout = ($hasEditor || $hasSelector) ? 'iframe' : 'app';
        return view('admin.screens.files', [
            'layout' => $layout,
            'hasEditor' => $hasEditor,
            'hasSelector' => $hasSelector
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function browser(Request $request)
    {
        return response()->json($this->fileService->browser(admin(), $request->input('directory', '')));
    }

    /**
     * @param UploadFileRequest $request
     * @return JsonResponse
     */
    public function upload(UploadFileRequest $request)
    {
        return response()->json($this->fileService->upload($request, admin()));
    }

    public function makeDirectory(Request $request)
    {
        return response()->json($this->fileService->makeDirectory($request->all(), admin()));
    }

    /**
     * @param ResizeImageRequest $request
     * @return JsonResponse
     */
    public function resize(ResizeImageRequest $request)
    {
        $user = Auth::user();
        return response()->json($this->fileService->resizeImage(
            $request->input('files', []),
            $request->input('width'),
            $request->input('height'),
            $user
        ));
    }

    /**;
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        return response()->json($this->fileService->delete($request->input('files', []), $request->input('directories', [])));
    }

}
