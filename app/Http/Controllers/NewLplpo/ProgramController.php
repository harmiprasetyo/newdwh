<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use App\Models\NewLplpo\Program;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ProgramController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        return view('newlplpo.program.index');
    }


    /**
     * DATATABLE
     */
    public function datatable(Request $request)
    {
        $query = Program::query()
            ->select([
                'id',
                'program_name',
                'created_at',
                'updated_at'
            ]);

        return DataTables::of($query)

            ->addIndexColumn()

            ->editColumn('created_at', function ($row) {

                if (!$row->created_at) {
                    return '-';
                }

                return $row->created_at->format('d-m-Y H:i');

            })

            ->addColumn('action', function ($row) {

                return '

                    <div class="dropdown">

                        <button
                            class="btn btn-sm btn-light border"
                            type="button"
                            data-bs-toggle="dropdown">

                            <i class="bi bi-three-dots-vertical"></i>

                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>

                                <button
                                    type="button"
                                    class="dropdown-item btn-edit"
                                    data-id="' . $row->id . '">

                                    <i class="bi bi-pencil-square me-2"></i>
                                    Edit

                                </button>

                            </li>

                            <li>

                                <button
                                    type="button"
                                    class="dropdown-item text-danger btn-delete"
                                    data-id="' . $row->id . '"
                                    data-name="' . e($row->program_name) . '">

                                    <i class="bi bi-trash me-2"></i>
                                    Hapus

                                </button>

                            </li>

                        </ul>

                    </div>

                ';
            })

            ->rawColumns([
                'action'
            ])

            ->make(true);
    }


    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'program_name' => [
                'required',
                'string',
                'max:255',
                'unique:new_lplpo_program_list,program_name'
            ]

        ], [

            'program_name.required' =>
                'Nama program wajib diisi.',

            'program_name.unique' =>
                'Nama program sudah tersedia.',

        ]);


        $program = Program::create([
            'program_name' =>
                trim($validated['program_name'])
        ]);


        return response()->json([

            'success' => true,

            'message' =>
                'Program berhasil ditambahkan.',

            'data' => $program

        ], 201);
    }


    /**
     * SHOW
     */
    public function show($id)
    {
        $program = Program::findOrFail($id);

        return response()->json([

            'success' => true,

            'data' => $program

        ]);
    }


    /**
     * UPDATE
     */
    public function update(
        Request $request,
        $id
    ) {

        $program = Program::findOrFail($id);


        $validated = $request->validate([

            'program_name' => [

                'required',

                'string',

                'max:255',

                Rule::unique(
                    'new_lplpo_program_list',
                    'program_name'
                )->ignore($program->id)

            ]

        ], [

            'program_name.required' =>
                'Nama program wajib diisi.',

            'program_name.unique' =>
                'Nama program sudah tersedia.',

        ]);


        $program->update([

            'program_name' =>
                trim($validated['program_name'])

        ]);


        return response()->json([

            'success' => true,

            'message' =>
                'Program berhasil diperbarui.',

            'data' => $program->fresh()

        ]);
    }


    /**
     * DELETE
     */
    public function destroy($id)
    {
        $program = Program::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | CHECK RELATION
        |--------------------------------------------------------------------------
        */

        if ($program->items()->exists()) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Program tidak dapat dihapus karena sudah digunakan oleh item LPLPO.'

            ], 422);

        }


        $program->delete();


        return response()->json([

            'success' => true,

            'message' =>
                'Program berhasil dihapus.'

        ]);
    }
}
