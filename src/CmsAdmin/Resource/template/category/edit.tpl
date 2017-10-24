{headScript()->appendFile('/resource/cmsAdmin/js/jquery-ui/jquery-ui.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Zarządzanie treścią#}</strong>
                    </div>
                    <div class="card-body">
                        <div id="categoryTreeContainer">
                            <div id="jstree">
                                {jsTree([], $baseUrl . '/resource/cmsAdmin/js/tree.js')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                {if $categoryForm}
                {$attributeCount = 0}
                {* zliczanie atrybutów *}
                {foreach $categoryForm->getElements() as $element}
                {if php_substr($element->getName(), 0 ,12) != 'cmsAttribute'}{continue}{/if}
                {$attributeCount++}
                {/foreach}
                {$categoryForm->start()}
                <div id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="card">
                        <div class="card-header" role="tab" id="headingOne">
                            <h5 class="mb-0">
                                <a data-toggle="collapse" data-parent="#accordion" href="#tab-config" aria-expanded="true" aria-controls="collapseOne">
                                    {#Konfiguracja#}
                                </a>
                            </h5>
                        </div>

                        <div id="tab-config" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                            <div class="card-block">
                                {$categoryForm->getElement('cmsCategoryTypeId')}
                                {$categoryForm->getElement('cmsCategoryTypeChanged')}
                                {if $duplicateAlert}<span class="red">Nazwa jest zduplikowana, treść może nie wyświetlać się poprawnie</span>{/if}
                                {$categoryForm->getElement('name')}
                                {$categoryForm->getElement('dateStart')}
                                {$categoryForm->getElement('dateEnd')}
                                {$categoryForm->getElement('cacheLifetime')}
                                {$categoryForm->getElement('active')}
                                {$categoryForm->getElement('submit1')}
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingTwo">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#tab-content" aria-expanded="false" aria-controls="collapseTwo">
                                    {#Atrybuty#}
                                </a>
                            </h5>
                        </div>
                        <div id="tab-content" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="card-block">
                                {foreach $categoryForm->getElements() as $element}
                                {if php_substr($element->getName(), 0 ,12) != 'cmsAttribute'}{continue}{/if}
                                {$element}
                                {/foreach}
                                {$categoryForm->getElement('submit3')}
                             </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#tab-advanced" aria-expanded="false" aria-controls="collapseThree">
                                    {#Zaawansowane#}
                                </a>
                            </h5>
                        </div>
                        <div id="tab-advanced" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="card-block">
                                {$categoryForm->getElement('redirectUri')}
                                {$categoryForm->getElement('mvcParams')}
                                {$categoryForm->getElement('configJson')}
                                {$categoryForm->getElement('https')}
                                {$categoryForm->getElement('blank')}
                                {$categoryForm->getElement('submit4')}
                            </div>
                        </div>
                    </div>
                    {$categoryForm->end()}
                    {$categoryId = $categoryForm->getRecord()->id}
                    {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' =>
                    'preview'])}
                    <div class="card">
                        <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#tab-widget" aria-expanded="false" aria-controls="collapseThree">
                                    {#Widgety#}
                                </a>
                            </h5>
                        </div>
                        <div id="tab-widget" class="collapse" role="tabpanel" aria-labelledby="collapseFour">
                            <div class="card-block">
                                {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action'
                                => 'add'])}<a
                                        href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=add&id={$categoryId}@}"
                                        class="button new-window" target="_blank"><i class="icon-plus"></i> dodaj widget</a>{/if}
                                <div id="widget-list-container" data-category-id="{$categoryId}">
                                    {widget('cmsAdmin', 'categoryWidgetRelation', 'preview', ['categoryId' => $categoryId])}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#tab-preview" aria-expanded="false" aria-controls="collapseThree">
                                    {#Podgląd#}
                                </a>
                            </h5>
                        </div>
                        <div id="tab-preview" class="collapse" role="tabpanel" aria-labelledby="collapseFive">
                            <div class="card-block">
                                <iframe id="preview-frame" src="{$categoryForm->getRecord()->getUrl()}?preview=1"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                {/if}
                {/if}
            </div>
        </div>
    </div>
</div>
