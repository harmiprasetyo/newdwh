<?php

namespace App\Http\Controllers\Api;
//di rename
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ListTypeFaskes;

class ListTypeFaskesController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => ListTypeFaskes::all()
        ]);
    }

    public function store(Request $request)
    {
        ListTypeFaskes::create($request->all());

        return response()->json(['message' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = ListTypeFaskes::findOrFail($id);
        $data->update($request->all());

        return response()->json(['message' => 'updated']);
    }

    public function destroy($id)
    {
        ListTypeFaskes::findOrFail($id)->delete();

        return response()->json(['message' => 'deleted']);
    }
}
