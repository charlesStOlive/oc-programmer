<?php namespace Waka\Programer\Models;

use Model;
use Carbon\Carbon;
use Mjml\Client as MjmlClient;

/**
 * campagne Model
 */

class Campagne extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Waka\Utils\Classes\Traits\DataSourceHelpers;
    use \Waka\Utils\Classes\Traits\WakaWorkflowTrait;
    use \Waka\Utils\Classes\Traits\DbUtils;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'waka_programer_campagnes';

    public $implement = [
        'October.Rain.Database.Behaviors.Purgeable',
    ];
    public $purgeable = [
        'ds_create',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id'];

    /**
     * @var array Fillable fields
     */
    //protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique',
        'wakaMail' => 'required',
    ];

    public $customMessages = [
    ];

    /**
     * @var array attributes send to datasource for creating document
     */
    public $attributesToDs = [
        'nb_targets',
        'nb_targets_eligible',
    ];


    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'scopes',
        'tests_ids',
        'pjs',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [
    ];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
    ];
    public $hasMany = [
        'mailLog' => ['Waka\Mailer\Models\MailLog'],
    ];
    public $hasOneThrough = [
    ];
    public $hasManyThrough = [
    ];
    public $belongsTo = [
       'wakaMail' => ['Waka\Mailer\Models\WakaMail'],
       'layout' => ['Waka\Mailer\Models\Layout'],
    ];
    public $belongsToMany = [
    ];        
    public $morphTo = [];
    public $morphOne = [
    ];
    public $morphMany = [
        'rule_asks' => [
            'Waka\Utils\Models\RuleAsk',
            'name' => 'askeable',
            'delete' => true
        ],
        'rule_fncs' => [
            'Waka\Utils\Models\RuleFnc',
            'name' => 'fnceable',
            'delete' => true
        ],
        'sends' => [
            'Waka\Lp\Models\SourceLog',
            'name' => 'sendeable',
            'delete' => true
        ],
        'sendBoxs' => [
            'Waka\Mailer\Models\SendBox',
            'name' => 'maileable'
        ],
    ];
    public $attachOne = [
    ];
    public $attachMany = [
    ];

    //startKeep/

    /**
     *EVENTS
     **/
    public function beforeSave() {
        if ($this->is_mjml && $this->mjml) {
            //transformation du mjmm en html via api mailjet.
            $applicationId = env('MJML_API_ID');
            $secretKey = env('MJML_API_SECRET');
            $clientMjml = new MjmlClient($applicationId, $secretKey);
            //constructtion du mjml final avec les blocs.

            $this->mjml_html = $clientMjml->render($this->mjml);
        }
    }


    /**
     * LISTS
     **/
    public function listDurations() {
        return \Config::get('waka.lp::durations');
    }
    //
    public function listActiveWakaMail() {
        if($this->ds_create) {
            return \Waka\Mailer\Models\WakaMail::active()->where('data_source', $this->ds_create)->where('is_campagner', true)->lists('name', 'id');
        } else {
            return [];
        }
        
    }

    public function listCampagneDataSource() {
        return \DataSources::list('campagne');
    }

    public function listProgramationOption() {
        $pms = \Config::get('waka.programer::programationMode');
        $array = [];
        foreach($pms as $key=>$pm) {
            $array[$key] = $pm['label'];

        }
        return $array;
    }
    public function listDays() {
        if($this->programation_mode) {
            //trace_log($this->programation_mode);
            $mode =  \Config::get('waka.programer::programationMode.'.$this->programation_mode);
            $opt = $mode['options']['day'];
            $days = \Config::get($opt);
            //trace_log($days);
            if($opt == 'waka.programer::num_day') {
                for($i=2; $i<28; $i++) {
                    $days[$i] = $i;
                }
            }
            return $days;
        } else {
            return [];
        }
        
    }
    public function programationTimeOk() {
        $date = Carbon::now(); 
        if(!$this->has_programmation) {
            return false;
        }
        if($this->programation_hour != $date->hour .':'.$date->minute) {
            return false;
        }
        if($this->programation_mode =='weekly') {
            if($this->programation_day == 'weekdays') {
                if(in_array($date->dayOfWeek, ['1', '2', '3', '4', '5'])) {
                    return true;
                }
            } else {
                if($date->dayOfWeek ==  $this->programation_day) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        elseif($this->programation_mode =='weekly') {
            if($this->programation_day == 'weekdays') {
                if(in_array($date->dayOfWeek, ['1', '2', '3', '4', '5'])) {
                    return true;
                }
            } else {
                if($date->dayOfWeek ==  $this->programation_day) {
                    return true;
                } else {
                    return false;
                }
            }
        } elseif($this->programation_mode =='monthly') {
            if($this->programation_day == 'firstDay') {
                if($date->copy()->startOfmonth() == $date) return true;
            } elseif($this->programation_day =='lastDay') {
                if($date->copy()->endOfmonth() == $date) return true;
            } else {
                if($date->dayOfWeek == $this->programation_day) return true;
            } 
        }
        elseif($this->programation_mode =='dailyAt') {
            if($date->dayOfWeek == $this->programation_day) return true;
        }else {
            \Log::error('programation mode erreur');
        }

    }
    public function filterState($column = null, $if = true, $initialStates = []) {
        $states = [];
        if($initialStates == []) {
            $states = \Config::get('waka.programer::campagne_state');
        } else {
            $states = $initialStates;
        }
        if(!$column) {
            return $states;
        }
        $filteredState = [];
        foreach($states as $key=>$state) {
            $columnValue = $state[$column] ?? false;
            if($columnValue ==  $if) {
                $filteredState[$key] = $key;
            }
        }
        return $filteredState;
        
    }

    // public function listStates() {
    //     if(!$this->state or $this->state == 'Brouillon') {
    //         return $this->filterState('init', true);
    //     }
    //     if($has_programmation) {
    //         return $this->filterState('only_progamation', true);
    //     }
    //     return \Config::get('waka.programer::campagne_state');
    // }
    public function listSelectionModes() {
        return \DataSources::find($this->data_source)->getScopesLists();
    }
    public function listSelectionNames() {
        if($this->selection_mode) {
            return \DataSources::find($this->data_source)->getScopeOptions($this->selection_mode);
        } else {
            return [];
        }
        
    }

    /**
     * GETTERS
     **/
    public function getDsNameAttribute() {
        if($this->wakaMail) {
            return $this->wakaMail->data_source;
        }
    }
    public function getNbTargetsAttribute() {
        $query = $this->getFinalQuery();
        if($query) {
            return $query->count();
        } else {
            return 'Attente config';
        }
        
    }
    public function getNbTargetsEligibleAttribute() {
        $query = $this->getFinalQuery();
        if($query) {
            return $query->eligibleEmail()->count();
        } else {
            return 'Attente config';
        }
    }
    public function getEligibles() {
        return  $this->getFinalQuery()->eligibleEmail();
    }

    /**
     * SCOPES
     */

    /**
     * SETTERS
     */
 
    /**
     * FILTER FIELDS
     */
    public function filterFields($fields, $context = null)
    {
        if($context == 'create') {
            if(isset($fields->wakaMail) && isset($fields->name) && isset($fields->slug)) {
                if($this->wakaMail) {
                    $date = \Carbon\Carbon::now()->format('y-m-d');
                    $wakaMail = $this->wakaMail;
                    $fields->name->value = "Campagne depuis l'email  ".$wakaMail->name." ".$date;
                    $fields->slug->value = str_slug($fields->name->value);
                }
            }

        }
        if(isset($fields->has_programmation) && isset($fields->state)) {
                if($fields->has_programmation->value == true) {
                    $fields->state->options = $this->filterState('progamation', true);
                } else {
                    $fields->state->options = $this->filterState('one_shot', true);
                }
            }
        if(isset($fields->programation_mode)) {
            if($fields->programation_mode->value) {
                $modeOpt = \Config::get('waka.programer::programationMode.'.$fields->programation_mode->value);
                if($modeOpt['options']['day'] ?? false) {
                    $fields->programation_day->hidden = false;
                } else {
                    $fields->programation_day->hidden = true;
                }
                if($modeOpt['options']['hour'] ?? false) {
                    $fields->programation_hour->hidden = false;
                } else {
                    $fields->programation_hour->hidden = true;
                }
            } else {
                 $fields->programation_day->hidden = true;
                 $fields->programation_hour->hidden = true;
            }
        }

        if(isset($fields->selection_mode)) {
            if($fields->selection_mode->value) {
                $modeOpt = \Config::get('waka.programer::selectionMode.'.$fields->selection_mode->value);
                $fields->selection_name->label = "Nom ".$fields->selection_mode->value;
            }
        }

        if(isset($fields->asks_modifiers) && $this->wakaMail) {
            $asks = $this->wakaMail->asks;
            //trace_log($asks);
        }
    }

    /**
     * OTHERS
     */
    public function getFinalQuery() {
        $ds = \DataSources::find($this->data_source);
        if(!$ds) return null;
        $scopeName = $this->selection_mode;
        if(!$scopeName) return null;
        $attribut = $this->selection_name;
        $attribut = $attribut == 'no' ? null : $attribut;
        //trace_log($attribut);
        //trace_log($scopeName);
        return $ds->class::$scopeName($attribut);
    }
    public function syncBase($context = null) {
        //trace_log('syncronisation de email');
        $originalMail = $this->wakaMail;
        if(!$originalMail) {
            throw new \ApplicationException('Mail original inexistant');
        }
        $this->layout = $originalMail->layout;
        $this->subject = $originalMail->subject;
        $this->data_source = $originalMail->data_source;
        $this->is_mjml = $originalMail->is_mjml;
        $this->mjml_html = $originalMail->mjml_html;
        $this->mjml = $originalMail->mjml;
        $this->html = $originalMail->html;
        $this->pjs = $originalMail->pjs;
        $this->is_scope = $originalMail->is_scope;
        $this->scopes = $originalMail->scopes;
        $this->use_key = $originalMail->use_key;
        $this->key_duration = $originalMail->key_duration;
        $this->has_sender = $originalMail->has_sender;
        $this->sender = $originalMail->sender;
        $this->reply_to = $originalMail->reply_to;
        $this->has_log = $originalMail->has_log;
        $this->open_log = $originalMail->open_log;
        $this->click_log = $originalMail->click_log;
        $this->lp = $originalMail->lp;
        $this->is_embed = $originalMail->is_embed;
    }
    
    public function syncRelations() {
        $originalMail = $this->wakaMail;
        $relations = ['rule_asks', 'rule_fncs'];
        
        foreach($relations as $relation) {
            $items = $this->{$relation};
            foreach($items as $item) {
                $item->delete();
            }
        }

        foreach($relations as $relation) {
            $items = $originalMail->{$relation};
            //trace_log($items->toArray());
            foreach($items as $item) {
                $newItem = $item->replicate();
                $this->{$relation}()->save($newItem);
            }
        }
    }

        

        

//endKeep/
}