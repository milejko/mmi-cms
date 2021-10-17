<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

use Mmi\Form\Element\ElementAbstract;

/**
 * Element wielokrotny upload
 */
class MultiUpload extends MultiField
{
    //pliki js i css
    private const MULTIUPLOAD_CSS_URL = '/resource/cmsAdmin/css/multiupload.css';
    private const MULTIUPLOAD_JS_URL  = '/resource/cmsAdmin/js/multiupload.js';

    //przedrostek tymczasowego obiektu plików
    public const TEMP_OBJECT_PREFIX = 'tmp-';

    /**
     * Elementy formularza
     *
     * @var ElementAbstract[]
     */
    protected array $_elements = [];

    /**
     * Błędy elementów formularza
     *
     * @var array
     */
    protected array $_elementErrors = [];

    /**
     * Błędy zagnieżdzonych elementów formularza
     *
     * @var array
     */
    protected array $_elementNestedErrors = [];

    /**
     * Konstruktor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this
            ->addClass('multiupload')
            ->addElement(new Hidden('file'))
            ->addElement((new Text('filename'))->setLabel('Nazwa pliku'));
    }

    /**
     * @return string
     */
    public function fetchField(): string
    {
        $this->addScriptsAndLinks();

        return '<div id="' . $this->getId() . '-list" class="' . $this->getClass() . '">
            <label for="' . $this->getId() . '-add" class="upload-add-label">
                <i class="icon fa fa-5 fa-cloud-upload"></i>
                Kliknij lub upuść pliki w tym obszarze
                <input type="file" id="' . $this->getId() . '-add" class="upload-add" data-template="' . $this->getDeclaredName() . '">                
                <div class="multiupload-progress-bar"><div class="progress"></div></div>
            </label>
            ' . $this->renderList() . '            
            </div>';
    }

    /**
     * Renderer pol formularza
     *
     * @param array|null $itemValues
     * @param string     $index
     *
     * @return string
     */
    protected function renderListElement(?array $itemValues = null, string $index = '**'): string
    {
        $html = '<li class="field-list-item border mb-3 p-3">
            <div class="icons">
                <a href="#" class="btn-toggle" role="button">
                    <i class="fa fa-angle-down fa-2"></i>
                </a>
            </div>
        <section>';

        foreach ($this->getElements() as $element) {
            $element->setId($this->getId() . '-' . $index . '-' . $element->getBaseName());
            $element->setName($this->getName() . '[' . $index . '][' . $element->getBaseName() . ']');
            $element->setValue($itemValues[$element->getBaseName()] ?? null);
            $element->setErrors($this->_elementErrors[$index][$element->getBaseName()] ?? []);

            if ($element instanceof Checkbox) {
                $element->getValue() ? $element->setChecked() : $element->setChecked(false);
            }

            if ($element instanceof Hidden) {
                $element->setValue($itemValues[$element->getBaseName()] ?? '{{cmsFileId}}');
            }

            $html .= $element->__toString();
        }

        $html .= '</section>
            <div class="icons">
                <a href="#" class="sortable-handler" role="button">
                    <i class="fa fa-arrows fa-2"></i>
                </a>
                <a href="#" class="btn-active" role="button">
                    <i class="fa fa-eye fa-2"></i>
                </a>
                <a href="#" class="btn-remove" role="button">
                    <i class="fa fa-trash-o fa-2"></i>
                </a>
            </div>
        </li>';

        return trim(preg_replace('/\r|\n|\s\s+/', ' ', $html));
    }

    protected function addScriptsAndLinks(): void
    {
        parent::addScriptsAndLinks();

        $this->view->headLink()->appendStylesheet(self::MULTIUPLOAD_CSS_URL);
        $this->view->headScript()->appendFile(self::MULTIUPLOAD_JS_URL);
    }

    /**
     * @return string
     */
    protected function jsScript(): string
    {
        $listElement = addcslashes($this->renderListElement(), "'");
        $listType    = $this->getDeclaredName();

        $uploadUrl = '/cmsAdmin/upload/multiupload';
        $thumbUrl  = '/cmsAdmin/upload/multithumbnail';
        $id        = $this->getId();
        $object    = self::TEMP_OBJECT_PREFIX . $this->getObject();
        $objectId  = $this->getUploaderId();

        return <<<html
            $(document).ready(function() {
                multifieldListItemTemplate['$listType'] = '$listElement';
            });
            
            $(document).ready(function () {
                multiuploadInitLists(('.multiupload'));
            });

            function multiuploadInitLists(lists) {
                $(lists).each(function (index, list) {                
                    let containerId = $(list).attr('id');
                    multiuploadInitContainer(containerId);
                });
            }
            
            function multiuploadInitContainer(containerId){
                multiuploadInitThumbs(containerId);
                multiuploadInitAdd(containerId);
            }

            function multiuploadInitThumbs(containerId){
                $('#' + containerId + ' > .field-list > li').each(function(){                
                    multiuploadLoadThumb($(this).find('input[type=hidden]'));
                });
            }
                
            function multiuploadLoadThumb(sourceInput){
                if($(sourceInput).parent().find('.thumb').length < 1){
                    $.ajax({
                        url: "$thumbUrl",
                        type: "POST",
                        data: {
                            "cmsFileId": parseInt(sourceInput.attr('value'))
                        }
                    })
                    .done(function(response){
                        sourceInput.before('<div class="thumb"><img class="thumb-small" src="'+response.thumb+'" data-image="'+response.image+'"/><img class="thumb-big" src="'+response.image+'"/></div>');
                    });
                }
            }
            
            function multiuploadUpdateProgress(containerId, progress){
                let progressBar = $('#' + containerId).find('.multiupload-progress-bar .progress');
                progressBar.css('width',  progress + '%');
                
                if(progress === 100){
                    let icon = $('#' + containerId).find('.upload-add-label .icon');
                    icon.addClass('pulse');
                    setTimeout(function(){
                        icon.removeClass('pulse');
                    }, 300);
                }
            }

            function multiuploadInitAdd(containerId){
                $(document).off('change', '#' + containerId + ' .upload-add');
                $(document).on('change', '#' + containerId + ' .upload-add', function (e) {
                    e.preventDefault();
                    
                    let template = $(this).data('template');
                    let list = $(this).closest('.multifield').find('.field-list').first();
                    let uploadBar = $(this).closest('.multiupload').find('.multiupload-progress-bar');
                    
                    multiuploadUpdateProgress(containerId, 0);
                    setTimeout(function(){
                        uploadBar.addClass('active');
                    }, 100);
                
                    const chunkSize = 1024*512; 
                    
                    let reader = new FileReader();
                    let file = $(this).prop('files')[0];       
                    let total = file.size; 
                    let parts = Math.ceil(file.size / chunkSize);
                    let partsLoaded = 0;
                    let loaded = 0;
                    let blob = file.slice(0, chunkSize); 
                    let cmsFileId = 0;
                    
                    reader.readAsArrayBuffer(blob);             
                    reader.onload = function(e){            
                        let chunk = blob
                        let formData = new FormData();

                        formData.append('name', file.name);
                        formData.append('chunk', partsLoaded);
                        formData.append('chunks', parts);
                        formData.append('fileId', '$id');
                        formData.append('fileSize', total);
                        formData.append('formObject', '$object');
                        formData.append('formObjectId', '$objectId');
                        formData.append('cmsFileId', 0);
                        formData.append('filters[max_file_size]', 0);
                        formData.append('filters[prevent_duplicates]', false);
                        formData.append('filters[prevent_empty]', true);
                        formData.append('file', chunk);
                        
                        $.ajax({
                            url: "$uploadUrl",
                            type: "POST", 
                            processData: false,
                            contentType: false,
                            data: formData
                        })
                        .done(function(response){               
                            cmsFileId = response.cmsFileId;
                            loaded += chunkSize;          
                            partsLoaded += 1;     
                        
                            multiuploadUpdateProgress(containerId, loaded/total * 100);
            
                            if(loaded <= total){
                                blob = file.slice(loaded,loaded+chunkSize);
                                reader.readAsArrayBuffer(blob); 
                            } else {                   
                                multiuploadUpdateProgress(containerId, 100);
                                $(list).append(
                                    multifieldListItemTemplate[template]
                                        .replaceAll('**', $(list).children().length)
                                        .replaceAll('##', $(list).parents('.field-list-item').last().index())
                                        .replaceAll('{{cmsFileId}}', response.cmsFileId)
                                );
                                let newItem = $(list).children('.field-list-item').last();
                                newItem.find('.select2').select2();
                                multifieldInitContainer(containerId);
                                multiuploadInitContainer(containerId);
                                multifieldToggleActive(newItem);
                                
                                setTimeout(function(){
                                    uploadBar.removeClass('active');
                                }, 200);
                            }
                        });       
                    };
                });
            }
        html;
    }
}
