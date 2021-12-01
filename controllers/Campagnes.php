<?php namespace Waka\Programer\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Waka\Programer\Models\Campagne;

/**
 * Campagne Back-end Controller
 */
class Campagnes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Waka.Utils.Behaviors.BtnsBehavior',
        'Waka.Utils.Behaviors.SideBarUpdate',
        'Waka.ImportExport.Behaviors.ExcelImport',
        'Waka.ImportExport.Behaviors.ExcelExport',
        'Waka.Utils.Behaviors.WorkflowBehavior',
        'Waka.Utils.Behaviors.DuplicateModel',
    ];
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $btnsConfig = 'config_btns.yaml';
    public $duplicateConfig = 'config_duplicate.yaml';
    public $sideBarUpdateConfig = 'config_side_bar_update.yaml';
    public $workflowConfig = 'config_workflow.yaml'; 

    public $requiredPermissions = ['waka.programer.*'];
    //FIN DE LA CONFIG AUTO

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Waka.Programer', 'programer', 'side-menu-campagnes');
    }

    //startKeep/

    public function update($id)
    {
        $this->bodyClass = 'compact-container';
        return $this->asExtension('FormController')->update($id);
    }

    public function onReSync() {
        $model = \Waka\Programer\Models\Campagne::find($this->params[0]);
        $model->syncBase();
        $model->syncRelations();
        $model->save();
        return \Redirect::refresh();
    }

    public function onLoadPreviewMail() {
        $productorId = $this->params[0];
        $productor = Campagne::find($productorId);
        if(!$productor->tests_ids) {
            throw new \ValidationException(['tests_ids' => 'Vous devez choisir des cibles']);
        }
        $ds = \DataSources::find($productor->data_source);
        $models = $ds->class::whereIn('id', $productor->tests_ids)->get(['email', 'name', 'id'])->toArray();
        //trace_log($models);
        $this->vars['models'] = $models;
        $this->vars['productorId'] = $productorId;
        return $this->makePartial('popup_tests');
    }

    public function onLoadCampagneTestForm() {

        $modelId = $this->params[0];
    }

    //endKeep/
}

