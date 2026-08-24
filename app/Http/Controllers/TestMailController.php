<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Throwable;

class TestMailController extends Controller
{
    public function send()
    {
        try {

            Mail::raw(
                'Ini adalah email test dari aplikasi Laravel LPLPO.',
                function ($message) {

                    $message
                        ->to('dddp@wwf.id')
                        ->subject('Email Notifikasi Permintaan Approval LPLPO');

                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim.'
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
