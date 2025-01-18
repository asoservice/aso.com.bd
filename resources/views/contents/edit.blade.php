@extends('backend.layouts.master')

@section('title', 'Test')
@section('content')
    @include('test.form', ['action' => $routes['update'], 'method' => 'PUT', 'fields'=> $fields])
@endsection

@include('contents.common', ['type'=> 'edit'])