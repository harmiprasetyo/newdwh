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

function getFhirValue($r, $path = null)
{
    // kalau path spesifik diminta
    if ($path) {
        return data_get($r, $path);
    }

    // fallback universal
    return data_get($r, 'valueQuantity.value')
        ?? data_get($r, 'valueInteger')
        ?? data_get($r, 'valueString')
        ?? data_get($r, 'valueBoolean')
        ?? data_get($r, 'valueDateTime')
        ?? data_get($r, 'valueCodeableConcept.coding.0.display');
}
