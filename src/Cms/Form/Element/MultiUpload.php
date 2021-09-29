<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element wielokrotny upload
 */
class MultiUpload extends MultiField
{
    //pliki js i css
    const MULTIUPLOAD_CSS_URL = '/resource/cmsAdmin/css/multiupload.css';

    /**
     * Elementy formularza
     *
     * @var ElementAbstract[]
     */
    protected $_elements = [];

    /**
     * Błędy elementów formularza
     *
     * @var array
     */
    protected $_elementErrors = [];

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
                <img src="/resource/cmsAdmin/css/img/upload.png"/>
                Kliknij lub upuść pliki w tym obszarze
                <input type="file" id="' . $this->getId() . '-add" class="upload-add">
            </label>
            ' . $this->renderList() . '
            <div class="upload-bar"></div>
            
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
    }

    /**
     * @return string
     */
    protected function jsScript(): string
    {
        $listElement = addcslashes($this->renderListElement(), "'");
        $listId      = $this->getId() . '-list';
        $uploadUrl   = '/cmsAdmin/upload/multiupload';
        $thumbUrl    = '/cmsAdmin/upload/multithumbnail';
        $id          = $this->getId();

        return <<<html
            $(document).ready(function() {
                let list = $('#$listId > .field-list');
                
                $('.upload-add').on("change", function(){
                    let uploadBar = $(this).closest('.multiupload').find('.upload-progress');
                    uploadBar.show();
                    uploadBar.html(0);
                
                    const chunkSize = 1024*512; 
                    
                    let reader = new FileReader();
                    let file = $(this).prop('files')[0];       
                    let total = file.size; 
                    let parts = Math.ceil(file.size / chunkSize);
                    let partsLoaded = 0;
                    let loaded = 0;
                    let blob = file.slice(0, chunkSize); 
                    let cmsFileId = 0;
                    let objectId = list.children().length + 1;
                    
                    reader.readAsArrayBuffer(blob);             
                    reader.onload = function(e){            
                        let chunk = blob //{file:reader.result}
                        let formData = new FormData();

                        formData.append('name', file.name);
                        formData.append('chunk', partsLoaded);
                        formData.append('chunks', parts);
                        formData.append('fileId', objectId);
                        formData.append('fileSize', total);
                        formData.append('formObject', 'tmp-$id');
                        formData.append('formObjectId', objectId);
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
                            
                            uploadBar.html((loaded/total) * 100);
            
                            if(loaded <= total){
                                blob = file.slice(loaded,loaded+chunkSize);
                                reader.readAsArrayBuffer(blob); 
                            } else {
                                $(list).append('$listElement'.replaceAll('**', list.children().length).replaceAll('{{cmsFileId}}', response.cmsFileId));
                                $(list).children('.field-list-item').last().find('.select2').select2();
                                    
                                let fileInput = $(list).children('.field-list-item').last().find('input[type=hidden]');
                                loadThumb(fileInput);
                            }
                        });       
                    };
                });
                
                $(list).children('.field-list-item').find('input[type=hidden]').each(function(){
                    loadThumb($(this));
                });
                
                function loadThumb(sourceInput){
                    $.ajax({
                        url: "$thumbUrl",
                        type: "POST",
                        data: {
                            "cmsFileId": parseInt(sourceInput.attr('value'))
                        }
                    })
                    .done(function(response){
                        sourceInput.before('<div class="thumb"><img class="thumb-small" src="'+response.thumb+'"/><img class="thumb-big" src="'+response.image+'"/></div>');
                    });
                }
            });
        html;
    }
}
