<?php

namespace Kanboard\Plugin\Metadata;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

class Plugin extends Base
{
    public function initialize()
    {
        //Project
        $this->template->hook->attach('template:project:sidebar', 'metadata:project/sidebar');

        //Task
        $this->template->hook->attach('template:task:sidebar:information', 'metadata:task/sidebar');
        $this->template->hook->attach('template:board:task:icons', 'metadata:task/footer_icon');

        //User
        $this->template->hook->attach('template:user:sidebar:information', 'metadata:user/sidebar');

        // Add link to new plugin settings
        //$this->template->hook->attach('template:config:sidebar', 'Metadata:config/sidebar');
    }

    public function onStartup()
    {
        // Translation
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getClasses()
    {
        return [
            'Plugin\Metadata\Model' => [
                'MetadataTypeModel',
            ],
        ];
    }

    public function getPluginName()
    {
        return 'Metadata';
    }

    public function getPluginDescription()
    {
        return t('Manage Metadata');
    }

    public function getPluginAuthor()
    {
        return 'BlueTeck + Daniele Lenares';
    }

    public function getPluginVersion()
    {
        return '1.0.33.1';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/BlueTeck/kanboard_plugin_metadata';
    }
}
