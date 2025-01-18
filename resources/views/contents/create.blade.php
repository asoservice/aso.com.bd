@extends('backend.layouts.master')

@section('title', 'Test')
@section('content')
    @include('test.form', ['action' => $routes['store'], 'fields'=> $fields])
@endsection

@include('contents.common', ['type'=> 'create'])