<?php namespace Waka\Programer\Classes\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use \PhpOffice\PhpSpreadsheet\Shared\Date as DateConvert;
use Waka\Programer\Models\Campagne;

class CampagnesImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    //startKeep/
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if(!$row->filter()->isNotEmpty()) {
                continue;
            }
            $campagne = null;
            $id = $row['id'] ?? null;
            if($id) {
                $campagne = Campagne::find($id);
            }
            if(!$campagne) {
                $campagne = new Campagne();
            }
            $campagne->id = $row['id'] ?? null;
            $campagne->state = $row['state'] ?? null;
            $campagne->has_programmation = $row['has_programmation'] ?? null;
            $campagne->selection_mode = $row['selection_mode'] ?? null;
            $campagne->selection_name = $row['selection_name'] ?? null;
            $campagne->is_scope = $row['is_scope'] ?? null;
            $campagne->programation_mode = $row['programation_mode'] ?? null;
            $campagne->programation_day = $row['programation_day'] ?? null;
            $campagne->programation_hour = $row['programation_hour'] ?? null;
            $campagne->is_mjml = $row['is_mjml'] ?? null;
            $campagne->mjml = $row['mjml'] ?? null;
            $campagne->html = $row['html'] ?? null;
            $campagne->name = $row['name'] ?? null;
            $campagne->slug = $row['slug'] ?? null;
            $campagne->wakaMail_id = $row['wakaMail_id'] ?? null;
            $campagne->nb_targets = $row['nb_targets'] ?? null;
            $campagne->save();
        }
    }
    //endKeep/


    /**
     * SAUVEGARDE DES MODIFS MC
     */
//     public function collection(Collection $rows)
//     {
//        foreach ($rows as $row) {
//            if(!$row->filter()->isNotEmpty()) {
//                continue;
//            }
//            $campagne = null;
//            $id = $row['id'] ?? null;
//            if($id) {
//                $campagne = Campagne::find($id);
//             }
//             if(!$campagne) {
//                 $campagne = new Campagne();
//             }
//             $campagne->id = $row['id'] ?? null;
//             $campagne->has_programmation = $row['has_programmation'] ?? null;
//             $campagne->has_sender = $row['has_sender'] ?? null;
//             $campagne->sender = $row['sender'] ?? null;
//             $campagne->reply_to = $row['reply_to'] ?? null;
//             $campagne->scopes = json_decode($row['scopes'] ?? null);
//             $campagne->selection_mode = $row['selection_mode'] ?? null;
//             $campagne->selection_name = $row['selection_name'] ?? null;
//             $campagne->pjs = json_decode($row['pjs'] ?? null);
//             $campagne->is_scope = $row['is_scope'] ?? null;
//             $campagne->use_key = $row['use_key'] ?? null;
//             $campagne->key_duration = $row['key_duration'] ?? null;
//             $campagne->auto_send = $row['auto_send'] ?? null;
//             $campagne->has_log = $row['has_log'] ?? null;
//             $campagne->open_log = $row['open_log'] ?? null;
//             $campagne->click_log = $row['click_log'] ?? null;
//             $campagne->programation_mode = $row['programation_mode'] ?? null;
//             $campagne->programation_day = $row['programation_day'] ?? null;
//             $campagne->programation_hour = $row['programation_hour'] ?? null;
//             $campagne->is_mjml = $row['is_mjml'] ?? null;
//             $campagne->mjml = $row['mjml'] ?? null;
//             $campagne->html = $row['html'] ?? null;
//             $campagne->state = $row['state'] ?? null;
//             $campagne->name = $row['name'] ?? null;
//             $campagne->slug = $row['slug'] ?? null;
//             $campagne->wakaMail_id = $row['wakaMail_id'] ?? null;
//             $campagne->nb_use = $row['nb_use'] ?? null;
//             $campagne->nb_targets = $row['nb_targets'] ?? null;
//             $campagne->save();
//         }
//     }
}
