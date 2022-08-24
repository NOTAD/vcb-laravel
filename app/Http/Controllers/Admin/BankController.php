<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Bank\StoreBankRequest;
use App\Http\Requests\Admin\Bank\UpdateBankRequest;
use App\Models\Bank;
use App\Services\BankService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BankController extends Controller
{
    /** @var BankService */
    protected $bankService;

    /**
     * BankController constructor.
     * @param BankService $bankService
     */
    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        if (Gate::denies(Permissions::VIEW_BANK)) {
            return abort(403, 'Không có quyền');
        }
        return view('admin.screens.banks', [
            'banks' => $this->bankService->searchBank($request->all()),
            'keyword' => $request->input('search', ''),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        if (Gate::denies(Permissions::VIEW_BANK)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->bankService->searchBank($request->all()));
    }

    /**
     * @param StoreBankRequest $request
     * @return JsonResponse
     */
    public function store(StoreBankRequest $request)
    {
        if (Gate::denies(Permissions::EDIT_BANK)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->bankService->createBank($request->parameters()));
    }

    /**
     * @param Bank $bank
     * @param StoreBankRequest $request
     * @return JsonResponse
     */
    public function update(Bank $bank, UpdateBankRequest $request)
    {
        if (Gate::denies(Permissions::EDIT_BANK)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->bankService->updateBank($bank, $request->parameters()));
    }


    /**
     * @param Bank $bank
     * @return JsonResponse
     */
    public function delete(Bank $bank)
    {
        if (Gate::denies(Permissions::DELETE_BANK)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->bankService->deleteBank($bank));
    }

}
