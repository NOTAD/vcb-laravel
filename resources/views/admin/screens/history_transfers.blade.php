@extends('admin.layouts.app')
@section('content')
    <history-transfer-manager
        :transfers="{{ $transfers->toJson() }}"
        :transaction-status="{{ json_encode($transactionStatus) }}"
        :transaction-status-enum="{{ json_encode($transactionStatusEnum) }}"
        :user="{{ $_user->toJson()}}"
        :keyword="{{ json_encode($keyword) }}"
    ></history-transfer-manager>

@endsection
