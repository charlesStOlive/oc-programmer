<?php namespace Waka\Programer\Listeners;

use Carbon\Carbon;
use Waka\Utils\Classes\Listeners\WorkflowListener;

class WorkflowCampagneListener extends WorkflowListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($event)
    {
        //Evenement obligatoires
        $event->listen('workflow.campagne.guard', [$this, 'onGuard']);
        $event->listen('workflow.campagne.entered', [$this, 'onEntered']);
        $event->listen('workflow.campagne.afterModelSaved', [$this, 'onAfterSavedFunction']);
        //Evenement optionels à déclarer ici.
        //$event->listen('workflow.campagne.leave', [$this, 'onLeave']);
        //$event->listen('workflow.campagne.transition', [$this, 'onTransition']);
        //$event->listen('workflow.campagne.enter', [$this, 'onEnter']);
    }

    /**
     * Fonctions de Gard
     * Permet de bloquer ou pas une transition d'état
     * doit retourner true or false
     */
    public function isAutoSend($event, $args = null) {
        $blocked = true;
        $model = $event->getSubject();
        if($model->auto_send) {
            $blocked = false;
        }
        return $blocked;
    }
    // public function authorized($event, $args = null)
    // {
    //     $blocked = false;
    //     $model = $event->getSubject();
    //     $type = $args['name'];
    //     //A terminer
    //     return $blocked;
    // }
    // public function compareFieldDate($event, $args = null)
    // {
    //     $blocked = false;
    //     $model = $event->getSubject();
    //     $date = Carbon::now();
    //     $field = $args['field'];
    //     $mode = $args['mode'];
    //     if (!$model->{$field}) {
    //         return false;
    //     }
    //     if ($mode == 'gt') {
    //         if ($model->{$field}->gt($date)) {
    //             return true;
    //         }
    //     }
    //     if ($mode == 'lt') {
    //         if ($model->{$field}->lt($date)) {
    //             return true;
    //         }
    //     }
    //     if ($mode == 'gte') {
    //         if ($model->{$field}->gte($date)) {
    //             return true;
    //         }
    //     }
    //     if ($mode == 'lte') {
    //         //trace_log($model->{$field});
    //         //trace_log($date);
    //         if ($model->{$field}->lte($date)) {
    //             return true;
    //         }
    //     }

    //     return $blocked;
    // }

    /**
     * FONCTIONS DE TRAITEMENT PEUVENT ETRE APPL DANS LES FONCTIONS CLASSIQUES
     */

    public function initSyncBase($event, $args = null)
    {
        $model = $event->getSubject();

        $model->syncBase();
    }
    public function checkeady($event, $args = null)
    {
        $model = $event->getSubject();
        if(!$model->nbTargets) {
            throw new \ValidationExeption(['selection_mode' => 'Auncune cible trouvée']);
        }
        
    }


    

    /**
     * Fonctions de production de doc, pdf, etc.
     * passe par l'evenement afterModelSaved
     * 2 arguements $model et $arg
     * Ici les valeurs ne peuvent plus être modifié il faut passer par un traitement
     */
    public function syncAfterinit($model, $args = null)
    {
        $model->syncRelations();
    }

    public function send($model, $args = null)
    {
        //trace_log("send args");
        //trace_log($args);
        $dataForCron = [
            'productorId' => $model->id,
            'forceAuto' => $args['forceAuto'] ?? null,
            
        ]; 
        //trace_log($dataForCron);
        try {
            $job = new \Waka\Programer\Jobs\SendCampagne($dataForCron);
            $jobManager = \App::make('Waka\Wakajob\Classes\JobManager');
            $jobManager->dispatch($job, "Envoi d'une campagne");
            $this->vars['jobId'] = $job->jobId;
        } catch (Exception $ex) {
            \Log::error($ex);
        }
        
    }

    // public function sendNotification($model, $args = null)
    // {
    //     $subject = $model->name;
    //     $modelId = $model->id;
    //     $model = $model->toArray();
    //     $model = compact('model');
    //     $dotedModel = array_dot($model);

    //     //trace_log($dotedModel);

    //     $code = $args['code'];

    //     $datasEmail = [
    //         'emails' => $model->responsable->email,
    //         'subject' => "Notification de tâche",
    //     ];
    //     /**///trace_log($code . ' | ' . $toTarget . ' | ' . $to . ' | ');
    //     $mail = new \Waka\Mailer\Classes\MailCreator($code, true);
    //     $mail->renderMail($modelId, $datasEmail);
    // }

}
