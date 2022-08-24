@extends('admin.layouts.app')
@section('content')
    <bank-manager
            :banks="{{ json_encode($banks) }}"
            :bank-status="{{ json_encode($bankStatus) }}"
            :bank-status-enum="{{ json_encode($bankStatusEnum) }}"
            :user="{{ json_encode($_user) }}"
            :keyword="{{ json_encode($keyword) }}"
    ></bank-manager>
@endsection
