<?php

namespace App\Mail;

use App\Models\NewLplpo\InfoLinkApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LplpoApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public InfoLinkApproval $approval;

    public function __construct(InfoLinkApproval $approval)
    {
        $this->approval = $approval;
    }

    public function build()
    {
        $this->approval->loadMissing([
            'report',
            'kapus',
        ]);

        return $this
            ->subject(
                'LPLPO Menunggu Persetujuan - '
                . $this->approval->report->nama_faskes
            )
            ->view('emails.newlplpo.approval');
    }
}