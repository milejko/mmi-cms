{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-12">
                {if $categoryForm}
                {$categoryForm->start()}
                {$category = $categoryForm->getRecord()}
                <h5>{$category->name|stripTags}{if $template && _($template->getName())} ({_($template->getName())}){/if}</h5>
                <h6>{niceCategorySlug($category)}</h6>
                <div class="floating-buttons">
                    <a style="color: #fff;" href="{@module=cmsAdmin&controller=category&action=index&parentId={$category->parentId}&p={$request->p}@}" class="btn btn-secondary confirm" title="{#template.category.edit.cancel.alert#}">
                        <i class="icon-close"></i>
                        {#template.category.edit.cancel#}
                    </a>
                    {if $template && $previewUrl}
                    <button style="color: #fff;" id="cmsadmin-form-categoryform-submit-top" type="submit" class="btn btn-secondary" name="cmsadmin-form-categoryform[submit]" value="1">
                        <i class="icon-eyeglass"></i>
                        {#template.category.edit.preview#}
                    </button>
                    {/if}
                    <button style="color: #fff;" id="cmsadmin-form-categoryform-commit-top" type="submit" class="btn btn-primary" name="cmsadmin-form-categoryform[commit]" value="1">
                        <i class="icon-check"></i>
                        {#template.category.edit.commit#}
                    </button>
                </div>
                <ul class="nav nav-tabs mt-4" role="tablist" data-id="{$categoryForm->getRecord()->id}">
                    {foreach $categoryForm->getOption('tabs') as $tabKey => $tab}
                        {$tabContainsElement = false}
                        {foreach $categoryForm->getElements() as $element}
                            {if 'submit' == $element->getBasename() || 'commit' == $element->getBasename()}
                                {continue}
                            {/if}
                            {if $tabKey == 'default' && !$element->getOption('tab')}
                                {$tabContainsElement = true}{break}
                            {/if}
                            {if $tabKey == $element->getOption('tab')}
                                {$tabContainsElement = true}{break}
                            {/if}
                        {/foreach}
                        {if $tabContainsElement}
                            <li class="nav-item">
                                <a title="{_('template.category.edit.tab.' . $tab['label'])}" class="nav-link" data-toggle="tab" href="#{$tabKey}" role="tab" aria-controls="basic" aria-expanded="true"><i class="icon-{$tab.icon}"></i></a>
                            </li>
                        {/if}
                    {/foreach}
                    {if $category->template && aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'preview'])}
                        {$widgetData = widget('cmsAdmin', 'categoryWidgetRelation', 'preview', ['categoryId' => $category->id, 'uploaderId' => $request->uploaderId])}
                    {/if}
                    {if $widgetData}
                        <li class="nav-item">
                            <a title="{#template.category.edit.tab.widget#}" class="nav-link" data-toggle="tab" href="#widgets" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-layers"></i></a>
                        </li>
                    {/if}
                    <li class="nav-item">
                        <a title="{#template.category.edit.tab.history#}" class="nav-link" data-toggle="tab" href="#history" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-clock"></i></a>
                    </li>
                </ul>
                <div class="tab-content">
                    {foreach $categoryForm->getOption('tabs') as $tabKey => $tab}
                        <div class="tab-pane" id="{$tabKey}" role="tabpanel" aria-expanded="true">
                            {foreach $categoryForm->getElements() as $element}
                                {if 'submit' == $element->getBasename() || 'commit' == $element->getBasename()}
                                    {continue}
                                {/if}
                                {if ($tabKey == 'default' && !$element->getOption('tab')) || $tabKey == $element->getOption('tab')}
                                    {$element}
                                {/if}
                            {/foreach}
                        </div>
                    {/foreach}
                    {if $widgetData}
                        <div class="tab-pane" id="widgets" role="tabpanel" aria-expanded="false" style="padding-bottom: 0">
                            <div id="widget-list-container" data-category-id="{$category->id}">
                                {$widgetData}
                            </div>
                        </div>
                    {/if}
                    <div class="tab-pane" id="history" role="tabpanel" aria-expanded="false">
                        {$historyGrid}
                    </div>
                </div>
                {$categoryForm->end()}
            {/if}
            <div class="clear"><br /></div>
        </div>
    </div>
</div>
</div>
