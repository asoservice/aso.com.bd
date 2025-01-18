@extends('backend.layouts.master')

@section('title', 'Test')
@section('content')
    @include('contents.form', ['action' => $routes['store'], 'fields'=> $fields])
@endsection

@include('contents.common', ['type'=> 'create'])