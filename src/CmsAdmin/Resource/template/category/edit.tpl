{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-12">
                {if $categoryForm}
                {$categoryForm->start()}
                {$category = $categoryForm->getRecord()}
                <h5>{$categoryForm->getRecord()->name}{if $template} ({_($template->getName())}){/if}</h5>
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
                    {if $category->template && aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'preview'])}
                        <li class="nav-item">
                            <a title="{#template.category.edit.tab.widget#}" class="nav-link" data-toggle="tab" href="#widgets" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-layers"></i></a>
                        </li>
                    {/if}
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.history#}" class="nav-link" data-toggle="tab" href="#history" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-clock"></i></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="settings" role="tabpanel" aria-expanded="true">
                        {if $duplicateAlert}<div class="em">{#template.category.edit.duplicate.alert#}<br /><br /></div>{/if}
                        {foreach $categoryForm->getElements() as $element}
                            {if 'commit' == $element->getName() || 'submit' == $element->getName()}{continue}{/if}
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
