<?php
/**
 * Copyright (c) 2018 Viamage Limited
 * All Rights Reserved
 */

namespace Waka\Programer\Jobs;

use Waka\Wakajob\Classes\JobManager;
use Waka\Wakajob\Classes\RequestSender;
use Waka\Wakajob\Contracts\WakajobQueueJob;
use Winter\Storm\Database\Model;
use Viamage\CallbackManager\Models\Rate;
use Waka\Programer\Classes\CampagneCreator;
use Waka\Utils\Classes\DataSource;

/**
 * Class SendRequestJob
 *
 * Sends POST requests with given data to multiple target urls. Example of Wakajob Job.
 *
 * @package Waka\Wakajob\Jobs
 */
class SendCampagne implements WakajobQueueJob
{
    /**
     * @var int
     */
    public $jobId;

    /**
     * @var JobManager
     */
    public $jobManager;

    /**
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $updateExisting;

    /**
     * @var int
     */
    private $chunk;

    /**
     * @var string
     */
    private $table;

    /**
     * @param int $id
     */
    public function assignJobId(int $id)
    {
        $this->jobId = $id;
    }

    /**
     * SendRequestJob constructor.
     *
     * We provide array with stuff to send with post and array of urls to which we want to send
     *
     * @param array  $data
     * @param string $model
     * @param bool   $updateExisting
     * @param int    $chunk
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->updateExisting = true;
        $this->chunk = 1;
    }

    /**
     * Job handler. This will be done in background.
     *
     * @param JobManager $jobManager
     */
    public function handle(JobManager $jobManager)
    {
        /**
         * travail preparatoire sur les donnes
         */
        $productorId = $this->data['productorId'];
        $campagneCreator = CampagneCreator::find($productorId);
        $dataSourceCode = $campagneCreator->getProductor()->data_source;
        $ds = \DataSources::find($dataSourceCode);

        //Travail sur les données //finalquery retourne une requete 
        $targets  = $campagneCreator->getProductor()->getEligibles();
        
        trace_log(get_class($targets));
        /**
         * We initialize database job. It has been assigned ID on dispatching,
         * so we pass it together with number of all elements to proceed (max_progress)
         */
        $loop = 1;
        $jobManager->startJob($this->jobId, $targets->count());
        $send = 0;
        $scopeError = 0;
        $skipped = 0;
        // Fin inistialisation

        
        $targetsChuncked = $targets->get(['id'])->chunk($this->chunk);

        trace_log($targetsChuncked->toArray());
        
        try {
            foreach ($targetsChuncked as $chunk) {
                foreach ($chunk as $target) {
                    // TACHE DU JOB
                    if ($jobManager->checkIfCanceled($this->jobId)) {
                        $jobManager->failJob($this->jobId);
                        break;
                    }
                    $jobManager->updateJobState($this->jobId, $loop);
                    /**
                     * DEBUT TRAITEMENT **************
                     */
                    //trace_log("DEBUT TRAITEMENT **************");
                    $myCampain = CampagneCreator::find($productorId);
                    $myCampain->setModelId($target->id);
                    
                    $emails = $ds->getContact('to', $target->id);
                    if (!$emails) {
                        ++$skipped;
                        continue;
                    }
                    $datasEmail = [
                        'emails' => $emails,
                        'subject' => $this->data['subject'] ?? null,
                    ];
                    //trace_log($datasEmail);
                    
                    
                    $myCampain->renderMail($datasEmail);
                    ++$send;
                    /**
                     * FIN TRAITEMENT **************
                     */
                }
                $loop += $this->chunk;
            }
            $jobManager->updateJobState($this->jobId, $loop);
            $jobManager->completeJob(
                $this->jobId,
                [
                'Message' => $targets->count().' '. \Lang::get('waka.mailer::wakamail.job_title'),
                'waka.mailer::wakamail.job_send' => $send,
                'waka.mailer::wakamail.job_scoped' => $scopeError,
                'waka.mailer::wakamail.job_skipped' => $skipped,
                ]
            );
        } catch (\Exception $ex) {
            //trace_log("Exception");
            /**/trace_log($ex->getMessage());
            $jobManager->failJob($this->jobId, ['error' => $ex->getMessage()]);
        }
    }
}
