<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\logs\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    /**
     * Halaman Activity Log
     */
    public function index()
    {
        return view('adminpanel.activitylog.index');
    }


    /**
     * DataTables
     */
    public function datatable(Request $request)
    {
        $query = ActivityLog::query()
            ->orderByDesc('created_at');


        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->start_date
            );

        }


        if ($request->filled('end_date')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->end_date
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTER ACTION
        |--------------------------------------------------------------------------
        */

        if ($request->filled('action')) {

            $query->where(
                'action',
                $request->action
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTER MODULE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('module')) {

            $query->where(
                'module',
                $request->module
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTER USER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('user_id')) {

            $query->where(
                'user_id',
                $request->user_id
            );

        }


        return DataTables::of($query)

            ->addIndexColumn()


            /*
            |--------------------------------------------------------------------------
            | WAKTU
            |--------------------------------------------------------------------------
            */

            ->addColumn('waktu', function ($row) {

                return $row->created_at
                    ? $row->created_at->format('d-m-Y H:i:s')
                    : '-';

            })


            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            ->addColumn('action_badge', function ($row) {

                $class = match ($row->action) {

                    'create' => 'success',

                    'update' => 'warning',

                    'delete' => 'danger',

                    'login' => 'primary',

                    'logout' => 'secondary',

                    default => 'info',

                };


                return '<span class="badge bg-' . $class . '">'
                    . e(strtoupper($row->action))
                    . '</span>';

            })


            /*
            |--------------------------------------------------------------------------
            | MODULE
            |--------------------------------------------------------------------------
            */

            ->addColumn('module_label', function ($row) {

                return $row->module
                    ? e($row->module)
                    : '-';

            })


            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            ->addColumn('user_label', function ($row) {

                return $row->user_id
                    ? e($row->user_id)
                    : '<span class="text-muted">System</span>';

            })


            /*
            |--------------------------------------------------------------------------
            | DESCRIPTION
            |--------------------------------------------------------------------------
            */

            ->addColumn('description_label', function ($row) {

                return $row->description
                    ? e($row->description)
                    : '-';

            })


            /*
            |--------------------------------------------------------------------------
            | METHOD
            |--------------------------------------------------------------------------
            */

            ->addColumn('method_badge', function ($row) {

                $class = match ($row->method) {

                    'GET' => 'secondary',

                    'POST' => 'success',

                    'PUT',
                    'PATCH' => 'warning',

                    'DELETE' => 'danger',

                    default => 'dark',

                };


                return $row->method
                    ? '<span class="badge bg-' . $class . '">'
                        . e($row->method)
                        . '</span>'
                    : '-';

            })


            /*
            |--------------------------------------------------------------------------
            | DETAIL
            |--------------------------------------------------------------------------
            */

            ->addColumn('aksi', function ($row) {

                $oldValues = e(
                    json_encode(
                        $row->old_values ?? [],
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_UNICODE |
                        JSON_UNESCAPED_SLASHES
                    )
                );


                $newValues = e(
                    json_encode(
                        $row->new_values ?? [],
                        JSON_PRETTY_PRINT |
                        JSON_UNESCAPED_UNICODE |
                        JSON_UNESCAPED_SLASHES
                    )
                );


                $url = e(
                    $row->url ?? '-'
                );


                $userAgent = e(
                    $row->user_agent ?? '-'
                );


                $ipAddress = e(
                    $row->ip_address ?? '-'
                );


                $subjectType = e(
                    $row->subject_type ?? '-'
                );


                $subjectId = e(
                    $row->subject_id ?? '-'
                );


                return '
                    <button
                        type="button"
                        class="btn btn-sm btn-primary btnDetail"

                        data-id="' . $row->id . '"

                        data-action="' .
                            e($row->action) .
                        '"

                        data-module="' .
                            e($row->module ?? '-') .
                        '"

                        data-description="' .
                            e($row->description ?? '-') .
                        '"

                        data-ip="' .
                            $ipAddress .
                        '"

                        data-url="' .
                            $url .
                        '"

                        data-user-agent="' .
                            $userAgent .
                        '"

                        data-subject-type="' .
                            $subjectType .
                        '"

                        data-subject-id="' .
                            $subjectId .
                        '"

                        data-old="' .
                            $oldValues .
                        '"

                        data-new="' .
                            $newValues .
                        '"

                    >

                        <i class="fas fa-eye"></i>

                    </button>
                ';

            })


            ->rawColumns([
                'action_badge',
                'user_label',
                'method_badge',
                'aksi',
            ])


            ->make(true);
    }
}