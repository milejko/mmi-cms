{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-12">
                {if $categoryForm}
                {$categoryForm->start()}
                {$category = $categoryForm->getRecord()}
                <h5>{$categoryForm->getRecord()->name}</h5>
                <div class="float-right" style="margin-top: -60px;">
                    <br />
                    <button style="margin-right: 10px; color: #fff;" id="cmsadmin-form-category-submit-top" type="submit" class="btn btn-secondary" name="cmsadmin-form-category[submit]" value="1">
                        {#template.category.edit.preview#}
                    </button>
                    <button style="color: #fff;" id="cmsadmin-form-category-commit-top" type="submit" class="btn btn-primary" name="cmsadmin-form-category[commit]" value="1">
                        {#template.category.edit.commit#}
                    </button>
                </div>
                <div class="clear"></div>
                <ul class="nav nav-tabs" role="tablist" data-id="{$categoryForm->getRecord()->id}">
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.config#}" class="nav-link" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-expanded="true"><i class="icon-pencil"></i></a>
                    </li>
                    {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'preview'])}
                        <li class="nav-item">
                            <a title="{#template.category.edit.tab.widget#}" class="nav-link" data-toggle="tab" href="#widgets" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-layers"></i></a>
                        </li>
                    {/if}
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.advanced#}" class="nav-link" data-toggle="tab" href="#advanced" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-wrench"></i></a>
                    </li>
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.history#}" class="nav-link" data-toggle="tab" href="#history" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-clock"></i></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="settings" role="tabpanel" aria-expanded="true">
                        {$categoryForm->getElement('cmsCategoryTypeId')}
                        {$categoryForm->getElement('cmsCategoryTypeChanged')}
                        {$categoryForm->getElement('name')}
                        {if $duplicateAlert}<div class="em">{#template.category.edit.duplicate.alert#}<br /><br /></div>{/if}
                        {$categoryForm->getElement('active')}
                        {$categoryForm->getElement('title')}
                        {$categoryForm->getElement('description')}
                                                    {foreach $categoryForm->getElements() as $element}
                                {if php_substr($element->getName(), 0 ,12) != 'cmsAttribute'}{continue}{/if}
                                {$element}
                            {/foreach}

                    </div>
                    {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'preview'])}
                        <div class="tab-pane" id="widgets" role="tabpanel" aria-expanded="false">
                            <div id="widget-list-container" data-category-id="{$category->id}">
                                {widget('cmsAdmin', 'categoryWidgetRelation', 'preview', ['categoryId' => $category->id, 'uploaderId' => $request->uploaderId])}
                            </div>
                        </div>
                    {/if}
                    <div class="tab-pane" id="history" role="tabpanel" aria-expanded="false">
                        {$historyGrid}
                    </div>
                    <div class="tab-pane" id="advanced" role="tabpanel" aria-expanded="false">
                        {$categoryForm->getElement('customUri')}
                        {$categoryForm->getElement('follow')}
                        {$categoryForm->getElement('cacheLifetime')}
                        {$categoryForm->getElement('redirectUri')}
                        {$categoryForm->getElement('mvcParams')}
                        {$categoryForm->getElement('configJson')}
                        {$categoryForm->getElement('https')}
                        {$categoryForm->getElement('blank')}
                        {$categoryForm->getElement('dateStart')}
                        {$categoryForm->getElement('dateEnd')}
                        {$categoryForm->getElement('roles')}
                    </div>
                </div>
                <div class="float-right">
                    <br />
                    <button style="margin-right: 10px; color: #fff;" id="cmsadmin-form-category-submit" type="submit" class="btn btn-secondary" name="cmsadmin-form-category[submit]" value="1">
                        {#template.category.edit.preview#}
                    </button>
                    <button style="color: #fff;" id="cmsadmin-form-category-commit" type="submit" class="btn btn-primary" name="cmsadmin-form-category[commit]" value="1">
                        {#template.category.edit.commit#}
                    </button>
                </div>
                {$categoryForm->end()}
            {/if}
            <div class="clear"><br /></div>
        </div>
    </div>
</div>
</div>
