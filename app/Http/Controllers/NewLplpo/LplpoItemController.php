<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewLplpo\ItemService;

class LplpoItemController extends Controller
{

    protected ItemService $service;

    public function __construct(ItemService $service)
    {
        $this->service=$service;
    }

    public function defaultValue(Request $request)
{
    return response()->json(
        $this->service->defaultValue(
            $request->report_id,
            $request->kode_obat,
            $request->program_id
        )
    );
}


    /**
     * datatable
     */
    public function list($reportId)
    {

        return response()->json(

            $this->service->datatable($reportId)

        );

    }

    /**
     * simpan
     */
    public function store(Request $request)
    {

        $item=$this->service->create(

            $request->all()

        );

        return response()->json([

            'success'=>true,

            'data'=>$item

        ]);

    }

    /**
     * update
     */
    public function update(Request $request,$id)
    {

        $item=$this->service->update(

            $id,

            $request->all()

        );

        return response()->json([

            'success'=>true,

            'data'=>$item

        ]);

    }

    /**
     * delete
     */
    public function destroy($id)
    {

        $this->service->delete($id);

        return response()->json([

            'success'=>true

        ]);

    }

}
