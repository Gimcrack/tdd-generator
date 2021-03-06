<?php

use App\ImportedProductDetail;
use App\ImportedProductHeader;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @param $what
 * @param array $overrides
 * @param int $qty
 * @return Model|Collection|ImportedProductHeader|ImportedProductDetail
 */
function create($what, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = [];
    }
    return ($qty > 1) ?
        factory($what, $qty)->create($overrides) :
        factory($what)->create($overrides);
}

/**
 * @param $what
 * @param $state
 * @param array $overrides
 * @param int $qty
 * @return Model|ImportedProductHeader|ImportedProductDetail
 */
function create_state($what, $state, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = [];
    }
    return ($qty > 1) ?
        factory($what, $qty)->states($state)->create($overrides)->map->fresh() :
        factory($what)->states($state)->create($overrides)->fresh();
}

function create_array($what, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = [];
    }
    return ($qty > 1) ?
        factory($what, $qty)->create($overrides)->map->fresh()->map->toArray()->all() :
        factory($what)->create($overrides)->fresh()->toArray();
}


function make($what, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = null;
    }
    return ($qty > 1) ?
        factory($what, $qty)->make($overrides) :
        factory($what)->make($overrides);
}


function make_array($what, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = null;
    }
    return ($qty > 1) ?
        factory($what, $qty)->make($overrides)->map->getAttributes() :
        factory($what)->make($overrides)->getAttributes();
}
