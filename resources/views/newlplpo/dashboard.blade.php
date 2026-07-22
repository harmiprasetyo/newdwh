@extends('newlplpo.layouts.master')

@section('title','Dashboard')

@section('content')

<div class="row">

<div class="col-md-2">

<div class="card">

<div class="card-body">

<h4>{{ $draft }}</h4>

DRAFT

</div>

</div>

</div>

<div class="col-md-2">

<div class="card">

<div class="card-body">

<h4>{{ $terkirim }}</h4>

TERKIRIM

</div>

</div>

</div>

<div class="col-md-2">

<div class="card">

<div class="card-body">

<h4>{{ $terverifikasi }}</h4>

TERVERIFIKASI

</div>

</div>

</div>

<div class="col-md-2">

<div class="card">

<div class="card-body">

<h4>{{ $ditolak }}</h4>

DITOLAK

</div>

</div>

</div>


<div class="col-md-2">

<div class="card">

<div class="card-body">

<h4>{{ $selesai }}</h4>

SELESAI

</div>

</div>

</div>

</div>

@endsection
