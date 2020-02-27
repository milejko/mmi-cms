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
                    <button style="margin-right: 10px; color: #fff;" id="cmsadmin-form-categoryform-submit-top" type="submit" class="btn btn-secondary" name="cmsadmin-form-categoryform[submit]" value="1">
                        {#template.category.edit.preview#}
                    </button>
                    <button style="color: #fff;" id="cmsadmin-form-categoryform-commit-top" type="submit" class="btn btn-primary" name="cmsadmin-form-categoryform[commit]" value="1">
                        {#template.category.edit.commit#}
                    </button>
                </div>
                <div class="clear"></div>
                <ul class="nav nav-tabs" role="tablist" data-id="{$categoryForm->getRecord()->id}">
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.config#}" class="nav-link" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-expanded="true"><i class="icon-pencil"></i></a>
                    </li>
                    {if $category->template && aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'preview'])}
                        <li class="nav-item">
                            <a title="{#template.category.edit.tab.widget#}" class="nav-link" data-toggle="tab" href="#widgets" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-layers"></i></a>
                        </li>
                    {/if}
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.advanced#}" class="nav-link" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-expanded="true"><i class="icon-wrench"></i></a>
                    </li>
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.history#}" class="nav-link" data-toggle="tab" href="#history" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-clock"></i></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="basic" role="tabpanel" aria-expanded="true">
                        {if $duplicateAlert}<div class="em">{#template.category.edit.duplicate.alert#}<br /><br /></div>{/if}
                        {foreach $categoryForm->getElements() as $element}
                            {if 'basic' == $element->getOption('tab') || !$element->getOption('tab')}
                                {$element}
                            {/if}
                        {/foreach}
                    </div>
                    {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'preview'])}
                        <div class="tab-pane" id="widgets" role="tabpanel" aria-expanded="false" style="padding-bottom: 0">
                            <div id="widget-list-container" data-category-id="{$category->id}">
                                {widget('cmsAdmin', 'categoryWidgetRelation', 'preview', ['categoryId' => $category->id, 'uploaderId' => $request->uploaderId])}
                            </div>
                        </div>
                    {/if}
                    <div class="tab-pane" id="advanced" role="tabpanel" aria-expanded="true">
                        {foreach $categoryForm->getElements() as $element}
                            {if 'advanced' != $element->getOption('tab')}{continue}{/if}
                            {$element}
                        {/foreach}
                    </div>
                    <div class="tab-pane" id="history" role="tabpanel" aria-expanded="false">
                        {$historyGrid}
                    </div>
                </div>
                <div class="float-right">
                    <br />
                    <button style="margin-right: 10px; color: #fff;" id="cmsadmin-form-categoryform-submit" type="submit" class="btn btn-secondary" name="cmsadmin-form-categoryform[submit]" value="1">
                        {#template.category.edit.preview#}
                    </button>
                    <button style="color: #fff;" id="cmsadmin-form-categoryform-commit" type="submit" class="btn btn-primary" name="cmsadmin-form-categoryform[commit]" value="1">
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
