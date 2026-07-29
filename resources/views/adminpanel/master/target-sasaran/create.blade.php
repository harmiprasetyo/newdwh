@extends('layouts/admin')


@section('title','Tambah Target Sasaran')

@section('content')

<div class="container-fluid">

    <form action="{{ route('master.target-sasaran.store') }}" method="POST">

        @csrf

        @include('adminpanel.master.target-sasaran.form')

    </form>

</div>



@endsection

