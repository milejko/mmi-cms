{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-8">
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
                                <a data-toggle="collapse" data-parent="#accordion" href="#tab-config"
                                   aria-expanded="true" aria-controls="collapseOne">
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
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingTwo">
                            <h5 class="mb-0">
                                <a data-toggle="collapse" data-parent="#accordion" href="#tab-seo" aria-expanded="false"
                                   aria-controls="collapseTwo">
                                    {#SEO#}
                                </a>
                            </h5>
                        </div>

                        <div id="tab-seo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="card-block">
                                {$categoryForm->getElement('title')}
                                {$categoryForm->getElement('description')}
                                {$categoryForm->getElement('customUri')}
                                {$categoryForm->getElement('follow')}
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#tab-content"
                                   aria-expanded="false" aria-controls="collapseThree">
                                    {#Atrybuty#}
                                </a>
                            </h5>
                        </div>
                        <div id="tab-content" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="card-block">
                                {foreach $categoryForm->getElements() as $element}
                                {if php_substr($element->getName(), 0 ,12) != 'cmsAttribute'}{continue}{/if}
                                {$element}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                   href="#tab-advanced" aria-expanded="false" aria-controls="collapseThree">
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
                                {$categoryForm->getElement('roles')}
                                {$categoryForm->getElement('submit4')}
                            </div>
                        </div>
                    </div>
                    {$categoryId = $categoryForm->getRecord()->id}
                    {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'preview'])}
                    <div class="card">
                        <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#tab-widget"
                                   aria-expanded="false" aria-controls="collapseThree" id="category-widgets">
                                    {#Widgety#}
                                </a>
                            </h5>
                        </div>
                        <div id="tab-widget" class="collapse" role="tabpanel" aria-labelledby="collapseFour">
                            <div class="card-block">
                                {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation',
                                'action'
                                => 'add'])}<a
                                        href="javascript:void(0);"
                                        onclick="PopupCenter('{@module=cmsAdmin&controller=categoryWidgetRelation&action=add&id={$categoryId}@}');return false;"
                                        class="button btn btn-primary btn-block new-window" target="_blank"><i
                                            class="icon-plus"></i> dodaj widget</a>{/if}
                                <div id="widget-list-container" data-category-id="{$categoryId}">
                                    {widget('cmsAdmin', 'categoryWidgetRelation', 'preview', ['categoryId' =>
                                    $categoryId])}
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}
                </div>
               {$categoryForm->getElement('commit')}
               <a style="margin-right: 10px; color: #fff;" class="btn btn-dark float-right" href="{$categoryForm->getRecord()->getUrl()}?preview=1">podgląd</a>
               <input style="margin-right: 10px; color: #fff;" id="cmsadmin-form-category-submit" type="submit" class="btn btn-secondary float-right" name="cmsadmin-form-category[submit]" value="zapisz kopię roboczą" >
               {$categoryForm->end()}
               {/if}
               <div style="clear: both"><br /></div>
            </div>
        </div>
    </div>
</div>
