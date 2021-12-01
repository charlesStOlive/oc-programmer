<?php namespace Waka\Programer\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * test Form Widget
 */
class Test extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'waka_programer_test';

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('test');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/test.css', 'waka.programer');
        $this->addJs('js/test.js', 'waka.programer');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
