<?php
class Modal extends CWidget
{
    private $_assetsUrl;

    public $modalId;
    public $replacePlace;

    public $includedScriptFiles = array();

    public function run()
    {
        parent::run();

        $this->registerClientScript();
        $this->registerIncludedFiles();
        $this->render('modal');
    }

    public function registerClientScript()
    {
        $clientScript = Yii::app()->getClientScript();
        $assetsUrl = $this->getAssetsUrl();

        if($clientScript->getPackageBaseUrl('jquery') === false)
        {
            $clientScript->registerCoreScript('jquery');
        }

        $clientScript->registerCssFile(
            $assetsUrl . '/css/style.css', 'all'
        );

        $clientScript->registerScriptFile(
            $assetsUrl . '/js/main.js'
//            CClientScript::POS_END
        );

        $clientScript->registerScript(
            'widget-var',
            "var modalId = '$this->modalId', replacePath = '$this->replacePlace';",
            CClientScript::POS_HEAD
        );
    }

    public function registerIncludedFiles()
    {
        $clientScript = Yii::app()->getClientScript();
        foreach($this->includedScriptFiles as $script)
        {
            $clientScript->registerScriptFile($script, CClientScript::POS_END);
        }

    }

    public function getAssetsUrl()
    {

        if($this->_assetsUrl === null)
        {
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', false, -1, YII_DEBUG
            );
        }

        return $this->_assetsUrl;
    }
}