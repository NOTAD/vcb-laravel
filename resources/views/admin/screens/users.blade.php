@extends('admin.layouts.app')
@section('content')
   <user-manager
        :user="{{ json_encode($_user) }}"
        :users="{{ json_encode($users) }}"
   ></user-manager>
@endsection
