<?php

namespace Cms\App;

class CmsSkinConfig
{

    private $_templates = [];
    
    public function addTemplate(CmsTemplateConfig $templateConfig)
    {
        $this->_templates[$templateConfig->name] = $templateConfig;
        return $this;
    }

    public function getTemplates()
    {
        return $this->_templates;
    }

}