@php

$status = strtoupper($status ?? '');

$config = [

    'DRAFT' => [
        'label' => 'Draft',
        'class' => 'bg-secondary'
    ],

    'SUBMITED' => [
        'label' => 'Terkirim',
        'class' => 'bg-primary'
    ],

    'VERIFIED' => [
        'label' => 'Sudah Diverifikasi',
        'class' => 'bg-success'
    ],

    'REJECTED' => [
        'label' => 'Ditolak',
        'class' => 'bg-danger'
    ],

    'FINAL' => [
        'label' => 'Selesai',
        'class' => 'bg-dark'
    ],

];

$item = $config[$status] ?? [

    'label' => $status ?: '-',
    'class' => 'bg-light text-dark'

];

@endphp


<span class="badge {{ $item['class'] }}">

    {{ $item['label'] }}

</span>
