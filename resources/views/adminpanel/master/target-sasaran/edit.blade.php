@extends('layouts.admin')

@section('title','Edit Target Sasaran')

@section('content')

<div class="container-fluid">

<form
    action="{{ route('master.target-sasaran.update',$target_sasaran->id) }}"
    method="POST">

    @csrf
    @method('PUT')

    @include('adminpanel.master.target-sasaran.form')

</form>

</div>

@endsection
