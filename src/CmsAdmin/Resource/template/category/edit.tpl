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
            {if $categoryForm}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Zarządzanie treścią#}</strong>
                    </div>
                    {$attributeCount = 0}
                    {* zliczanie atrybutów *}
                    {foreach $categoryForm->getElements() as $element}
                    {if php_substr($element->getName(), 0 ,12) != 'cmsAttribute'}{continue}{/if}
                    {$attributeCount++}
                    {/foreach}
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab-config" href="#tab-config" role="tab">Konfiguracja</a>
                            </li>
                            {if $attributeCount > 0}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab-seo" href="#tab-seo" role="tab">Atrybuty</a>
                            </li>
                            {/if}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab-advanced" href="#tab-advanced" role="tab">Zaawansowane</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab-widget" href="#tab-widget" role="tab">Widgety</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab-preview" href="#tab-preview" role="tab">Podgląd</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            {$categoryForm->start()}
                            <div class="tab-pane active" id="tab-config" role="tabpanel">
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
                            {if $attributeCount > 0}
                            <div class="tab-pane" id="tab-seo" role="tabpanel">
                                {foreach $categoryForm->getElements() as $element}
                                {if php_substr($element->getName(), 0 ,12) != 'cmsAttribute'}{continue}{/if}
                                {$element}
                                {/foreach}
                                {$categoryForm->getElement('submit3')}
                            </div>
                            {/if}
                            <div class="tab-pane" id="tab-advanced" role="tabpanel">
                                {$categoryForm->getElement('redirectUri')}
                                {$categoryForm->getElement('mvcParams')}
                                {$categoryForm->getElement('configJson')}
                                {$categoryForm->getElement('https')}
                                {$categoryForm->getElement('blank')}
                                {$categoryForm->getElement('submit4')}
                            </div>
                            {$categoryForm->end()}
                            {$categoryId = $categoryForm->getRecord()->id}
                            {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' =>
                            'preview'])}
                            <div class="tab-pane" id="tab-widget" role="tabpanel">
                                {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action'
                                => 'add'])}<a
                                        href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=add&id={$categoryId}@}"
                                        class="button new-window" target="_blank"><i class="icon-plus"></i> dodaj widget</a>{/if}
                                <div id="widget-list-container" data-category-id="{$categoryId}">
                                    {widget('cmsAdmin', 'categoryWidgetRelation', 'preview', ['categoryId' => $categoryId])}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-preview" role="tabpanel">
                                <iframe id="preview-frame" src="{$categoryForm->getRecord()->getUrl()}?preview=1"></iframe>
                            </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
        </div>
    </div>
</div>