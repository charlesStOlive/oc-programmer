<?php namespace Waka\Programer\Classes;


use Waka\Mailer\Classes\MailCreator;
use Waka\Programer\Models\Campagne;

class CampagneCreator extends MailCreator
{
    public static $productor;
    public $ds;
    public $modelId = null;
    private $isTwigStarted;
    public $manualData = [];
    public $implement = [];
    public $askResponse = [];

    public static function find($mail_id, $slug = false)
    {
        //trace_log('find');
        $productor;
        if ($slug) {
            $productorModel = Campagne::where('slug', $mail_id)->first();
        } else {
            $productorModel = Campagne::find($mail_id);
        }
        if (!$productorModel) {
            /**/trace_log("Le code ou id  de la campagne ne fonctionne pas : " . $mail_id. "vous dever entrer l'id ou le code suivi de true");
            throw new ApplicationException("Le code ou id  email ne fonctionne pas : " . $mail_id. "vous dever entrer l'id ou le code suivi de true");
        }
        self::$productor = $productorModel;
        return new self;
    }

    public static function getProductor()
    {
        return self::$productor;
    }
}
