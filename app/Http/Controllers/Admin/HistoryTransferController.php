<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permissions;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryTransfer\StoreHistoryTransferRequest;
use App\Http\Requests\Admin\HistoryTransfer\UpdateHistoryTransferRequest;
use App\Jobs\ProcessCallback;
use App\Models\HistoryTransfer;
use App\Services\HistoryTransferService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class HistoryTransferController extends Controller
{
    /** @var HistoryTransferService */
    protected $historyTransferService;

    /**
     * GameController constructor.
     * @param HistoryTransferService $historyTransferService
     */
    public function __construct(HistoryTransferService $historyTransferService)
    {
        $this->historyTransferService = $historyTransferService;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|void
     */
    public function index(Request $request)
    {
        if (Gate::denies(Permissions::VIEW_HISTORY_TRANSFER)) {
            return abort(403, 'Không có quyền');
        }

        $search = $request->input('search', '');
        return view('admin.screens.history_transfers', [
            'transfers' => $this->historyTransferService->searchHistory($request->all()),
            'keyword' => $search
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function search(Request $request)
    {
        if (Gate::denies(Permissions::VIEW_HISTORY_TRANSFER)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->historyTransferService->searchHistory($request->all()));
    }

    /**
     * @param StoreHistoryTransferRequest $request
     * @return JsonResponse|void
     */
    public function store(StoreHistoryTransferRequest $request)
    {
        if (Gate::denies(Permissions::EDIT_HISTORY_TRANSFER)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->historyTransferService->createHistory($request->parameters()));
    }

    /**
     * @param HistoryTransfer $history
     * @param StoreProjectHistoryRequest $request
     * @return JsonResponse|void
     */
    public function update(HistoryTransfer $history, UpdateHistoryTransferRequest $request)
    {
        if (Gate::denies(Permissions::EDIT_HISTORY_TRANSFER)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->historyTransferService->updateHistory($history, $request->parameters()));
    }

    public function callback(HistoryTransfer $history)
    {
        if (Gate::denies(Permissions::VIEW_HISTORY_TRANSFER)) {
            abort(403, 'Không có quyền');
        }
        ProcessCallback::dispatchAfterResponse($history);
        return response()->json($history);
    }

    /**
     * @param HistoryTransfer $history
     * @return JsonResponse|void
     */
    public function delete(HistoryTransfer $history)
    {
        if (Gate::denies(Permissions::DELETE_HISTORY_TRANSFER)) {
            return abort(403, 'Không có quyền');
        }
        return response()->json($this->historyTransferService->deleteHistory($history));
    }

    /**
     * @param HistoryTransfer $history
     * @return JsonResponse|void
     */
    public function reset(HistoryTransfer $history)
    {
        if (Gate::denies(Permissions::EDIT_HISTORY_TRANSFER)) {
            return abort(403, 'Không có quyền');
        }
        $params = [
            'status' => TransactionStatus::PENDING
        ];
        return response()->json($this->historyTransferService->updateHistory($history, $params));
    }
}
