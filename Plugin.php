<?php namespace Waka\Programer;

use Backend;
use System\Classes\PluginBase;
use Lang;
use View;
use Event;
use Waka\Mailer\Models\WakaMail;
use Waka\Mailer\Controllers\WakaMails;

/**
 * programer Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'Waka.Utils',
        'Waka.Mailer',
    ];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'programer',
            'description' => 'No description provided yet...',
            'author'      => 'waka',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        
        \DataSources::registerDataSources(plugins_path().'/waka/programer/config/datasources.yaml');
        \Event::subscribe(new \Waka\Programer\Listeners\WorkflowCampagneListener());
        //Injection de is_campagner dans mailer.wakamail
        Event::listen('backend.form.extendFields', function ($widget) {

            // Only for the User controller
            if (!$widget->getController() instanceof WakaMails) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof WakaMail) {
                return;
            }

            if ($widget->alias == 'mailBehaviorformWidget') {
            return;
            }
            if ($widget->alias == 'myduplicateformWidget') {
                return;
            }
            if ($widget->alias == 'mailDataformWidget') {
                return;
            }
            if ($widget->alias == 'sideBarUpdateformWidget') {
                return;
            }
            if ($widget->context == 'create') {
                return;
            }

            // Add an extra birthday field
            $widget->addTabFields([
                'is_campagner' => [
                    'label' => 'waka.programer::campagne.is_campagner',
                    'type' => 'switch',
                    'permissions' => 'waka.programer.admin.*',
                    'tab' => 'waka.mailer::wakamail.tab_options',
                    'default' => false
                ],
            ]);
        });
        
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Waka\Programer\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'waka.programer.campagne.admin.super' => [
                'tab' => 'Waka - Programation/Campagne',
                'label' => 'Super Administrateur des campagnes',
            ],
            'waka.programer.campagne.admin.base' => [
                'tab' => 'Waka - Programation/Campagne',
                'label' => 'Administrateur des campagnes',
            ],
            'waka.programer.campagne.admin.super.user' => [
                'tab' => 'Waka - Programation/Campagne',
                'label' => 'Utilisateur des campagnes',
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'gestion' => [
                'label' => Lang::get('waka.programer::lang.menu.title'),
                'url' => Backend::url('waka/programer/campagnes'),
                'icon' => 'wicon-address',
                'permissions' => ['waka.programer.*'],
                'order' => 150,
                'sideMenu' => [
                    'side-menu-campagnes' => [
                        'label' => Lang::get('waka.programer::lang.menu.campagnes'),
                        'icon' => 'wicon-document-download',
                        'url' => Backend::url('wcli/programer/campagnes'),
                        'permissions' => ['waka.programer.*'],
                    ],
                ],
                
            ],
        ];
    }

    public function registerSettings()
    {
        return [];
    }
}
