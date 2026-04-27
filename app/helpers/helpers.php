<?php
function getCodes($obs)
{
    return collect($obs['resource']['code']['coding'] ?? [])
        ->pluck('code')
        ->toArray();
}




function getCategory($obs)
{
    return collect(data_get($obs, 'resource.category', []))
        ->flatMap(fn($c) => $c['coding'] ?? [])
        ->pluck('code');
}

function getVal($obs)
{
    return data_get($obs, 'resource.valueQuantity.value');
}

function getUnit($obs)
{
    return data_get($obs, 'resource.valueQuantity.unit');
}


function getValANC($obs)
{
    return data_get($obs, 'resource.valueInteger');
}


