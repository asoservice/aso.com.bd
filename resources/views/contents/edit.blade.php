@extends('backend.layouts.master')

@section('title', 'Test')
@section('content')
    @include('contents.form', ['action' => $routes['update'], 'method' => 'PUT', 'fields'=> $fields])
@endsection

@include('contents.common', ['type'=> 'edit'])