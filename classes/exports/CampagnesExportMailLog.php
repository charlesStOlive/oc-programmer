<?php namespace Waka\Programer\Classes\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
//
use Waka\Programer\Models\Campagne;

class CampagnesExportMailLog implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public $parentId;

    public function __construct($listId, $parentId)
    {
        $this->parentId = $parentId;
    }

    //startKeep/

    public function headings(): array
    {
        return [
            'id',
        ];
    }

    public function collection()
    {
        $parent = Campagne::find($this->parentId);
        $request = $parent->mailLog()->get($this->headings());
       
        
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
            'A1:A10' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFFFF00'],
                ],
            ],
        ];
    }

    //endKeep/
}