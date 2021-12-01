<?php namespace Waka\Programer\Classes\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
//
use Waka\Programer\Models\Campagne;

class CampagnesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public $listId;

    public function __construct($listId = null)
    {
        $this->listId = $listId;
    }

    //startKeep/

    public function headings(): array
    {
        return [
            'id',
            'state',
            'has_programmation',
            'selection_mode',
            'selection_name',
            'pjs',
            'is_scope',
            'scopes',
            'programation_mode',
            'programation_day',
            'programation_hour',
            'is_mjml',
            'mjml',
            'html',
            'name',
            'slug',
            'wakaMail_id',
            'nb_targets',
        ];
    }

    public function collection()
    {
        $request;
        if ($this->listId) {
            $request = Campagne::whereIn('id', $this->listId)->get($this->headings());
        } else {
            $request = Campagne::get($this->headings()); 
        }
        $request = $request->map(function ($item) {
                return $item;
        });;
        return $request;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A'    => ['font' => ['bold' => true]],
            1 => ['font' => ['bold' => true]],
            'A1:A50' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFFF00'],
                ],
            ],
        ];
    }

    //endKeep/



    /**
    * MAJ DU SYSTHEME *****************************
    **/
//     public function headings(): array
//     {
//         return [
//             'id',
//             'state',
//             'has_programmation',
//             'selection_mode',
//             'selection_name',
//             'pjs',
//             'is_scope',
//             'scopes',
//             'programation_mode',
//             'programation_day',
//             'programation_hour',
//             'is_mjml',
//             'mjml',
//             'html',
//             'name',
//             'slug',
//             'wakaMail_id',
//             'use_key',
//             'key_duration',
//             'has_log',
//             'open_log',
//             'click_log',
//             'has_sender',
//             'sender',
//             'replyTo',
//             'nb_use',
//             'nb_targets',
//         ];
//     }

//     public function collection()
//     {
//         $request;
//         if ($this->listId) {
//             $request = Campagne::whereIn('id', $this->listId)->get($this->headings());
//         } else {
//             $request = Campagne::get($this->headings()); 
//         }
//         $request = $request->map(function ($item) {
//                 return $item;
//         });;
//         return $request;
//     }
}
