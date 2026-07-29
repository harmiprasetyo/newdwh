<?php

namespace App\Services\Fhir;

use Illuminate\Support\Arr;
use App\Services\Fhir\ObservationFormatter;

class ObservationMapper
{

 protected ObservationFormatter $formatter;

    public function __construct(
        ObservationFormatter $formatter
    ) {
        $this->formatter = $formatter;
    }

protected array $map = [

    /*
        |--------------------------------------------------------------------------
        | VITAL SIGN
        |--------------------------------------------------------------------------
        */
         '8480-6' => [
            'field' => 'sistole',
        ],
         '8462-4' => [
            'field' => 'diastole',
        ],

         '8302-2' => [
            'field' => 'ANC.anc_body_heigh',
        ],
         '8867-4' => [
            'field' => 'VS.nadi',
        ],
         '8310-5' => [
            'field' => 'VS.suhubadan',
        ],

          '9279-1' => [
            'field' => 'VS.pernafasan',
        ],

        /*
        --------------------------------------------------------------------------
        | Social History
        |--------------------------------------------------------------------------
        */

          '72166-2' => [
            'field' => 'ANC.anc_smooking',
        ],
          '11331-6' => [
            'field' => 'ANC.anc_alch',
        ],

        /*
        |--------------------------------------------------------------------------
        | SURVEY
        |--------------------------------------------------------------------------
        */

        '11996-6' => [
            'field' => 'ANC.gravida',
        ],

        '11977-6' => [
            'field' => 'ANC.parity',
        ],

        '64708-1' => [
            'field' => 'ANC.parity',
        ],

        '69043-8' => [
            'field' => 'ANC.abortions',
        ],

        '8665-2' => [
            'field' => 'ANC.anc_hpht',
        ],

         '32418-6' => [
            'field' => 'ANC.anc_trimester',
        ],
         '56077-1' => [
            'field' => 'ANC.bb_pre',
        ],
         '18185-9' => [
            'field' => 'ANC.anc_usia_kehamilan',
        ],
         '11778-8' => [
            'field' => 'ANC.anc_hpl',
        ],
        'OC000001'=>[
            'field'=>'ANC.anc_jarak_hamil',
        ],








        '93857-1' => [
            'field' => 'INC.inc_dtd',
        ],
         'OC000013' => [
            'field' => 'INC.inc_penolong',
        ],
         '57071-3' => [
            'field' => 'INC.inc_cara_persalinan',
        ],
         '249197004' => [
            'field' => 'INC.inc_keadaan_ibu',
        ],
         '249120008' => [
            'field' => 'INC.inc_kala1',
        ],
         '249160009' => [
            'field' => 'INC.inc_kala2',
        ],
          'OC000018' => [
            'field' => 'INC.inc_kala3',
        ],
          'OC000019' => [
            'field' => 'INC.inc_kala4',
        ],
          '11881-0' => [
            'field' => 'ANC.anc_tfu',
        ],




        /*
        |--------------------------------------------------------------------------
        | LAB
        |--------------------------------------------------------------------------
        */

        '718-7' => [
            'field' => 'LAB.lab_hb',
        ],
         '10331-7' => [
            'field' => 'LAB.lab_rh',
        ],
         '5804-0' => [
            'field' => 'LAB.lab_urin_protein',
        ],
         '74774-1' => [
            'field' => 'LAB.lab_guladarah',
        ],
         '75410-1' => [
            'field' => 'LAB.lab_hepatitis_b',
        ],
         '68961-2' => [
            'field' => 'LAB.lab_hiv',
        ],

        /*---------------------------------------------------------------------------------------
                                             EXAM
        -----------------------------------------------------------------------------------------*/
         '284473002' => [
            'field' => 'ANC.anc_lila',
        ],
         'ANC.SS.DE26' => [
            'field' => 'ANC.anc_conjungtiva',
        ],
         'ANC.SS.DE27' => [
            'field' => 'ANC.anc_sklera',
        ],
          'ANC.SS.DE28' => [
            'field' => 'ANC.anc_leher',
        ],
         'ANC.SS.DE29' => [
            'field' => 'ANC.anc_mulut',
        ],
          'ANC.SS.DE30' => [
            'field' => 'ANC.anc_tht',
        ],
         'ANC.SS.DE31' => [
            'field' => 'ANC.anc_jantung',
        ],
          '10191-5' => [
            'field' => 'ANC.anc_perut',
        ],
          '246435002' => [
            'field' => 'ANC.anc_jumlah_janin',
        ],
          '89087-1' => [
            'field' => 'ANC.anc_tbj',
        ],
          '72155-5' => [
            'field' => 'ANC.anc_presentasi',
        ],
          '249111004' => [
            'field' => 'ANC.anc_head',
        ],
          '55283-6' => [
            'field' => 'ANC.anc_djj',
        ],



    ];

    public function map(array $observations, array &$dt): array
    {
        foreach ($observations as $obs) {

            foreach ($obs['codes'] as $coding) {

    $code = $coding['code'] ?? null;

    if (!$code) {
        continue;
    }

    if (!isset($this->map[$code])) {
        continue;
    }

    $config = $this->map[$code];

    Arr::set(
        $dt,
        $config['field'],
        $this->formatter->format(
            $obs['value'],
            $config['options'] ?? []
        )
    );

    // Sudah ketemu, tidak perlu cek coding lain
    break;
}
        }


        return $dt;
    }
}
