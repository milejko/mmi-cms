{foreach $sections as $section}
    <div class="card boxSection">
        <div class="card-header">
            <strong>{_($section->getName())}</strong>
        </div>
        <div class="card-body" id="widget-list-container">
            <div style="overflow-x: auto; white-space:nowrap;">
                {foreach $section->getAvailableWidgets() as $availableWidgetKey => $availableWidget}
                    {$displayButton = true}
                    {foreach $maxOccurenceWidgets as $key}{if $availableWidgetKey == $key}{$displayButton = false}{/if}{/foreach}
                    {if $displayButton}
                        <button id="cmsadmin-form-category-submit" type="submit" class="button btn btn-primary btn-inline-block" name="cmsadmin-form-category[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&widget={$availableWidgetKey}&action=edit&categoryId={$category->id}&originalUploaderId={$request->uploaderId}&originalId={$category->cmsCategoryOriginalId}@}">
                            <i class="icon-plus"></i> {_($availableWidget->getName())}
                        </button>
                    {/if}
                {/foreach}
            </div>
            {$widgetRelations = $category->getWidgetModel()->getWidgetRelationsBySectionKey($section->getKey())}
            <ul class="wlist ui-sortable widget-list" data-category-id="{$category->id}">
                {foreach $widgetRelations as $widgetRelation}
                    <li id="widget-item-{$widgetRelation->id}" class="ui-sortable-handle">
                        <div class="preview">
                            {categoryWidgetPreview($widgetRelation)}
                        </div>
                        <div class="operation">
                            {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'config'])}
                                <button class="button edit" type="submit" name="cmsadmin-form-category[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&action=edit&widget={$widgetRelation->widget}&id={$widgetRelation->id}&categoryId={$category->id}&originalUploaderId={$request->uploaderId}&uploaderId={$widgetRelation->id}&originalId={$category->cmsCategoryOriginalId}@}">
                                    <i class="fa fa-pencil-square-o pull-right fa-2"></i>
                                </button>
                            {/if}
                            {if $widgetRelation->active == 1}
                                {$class='fa fa-2 fa-eye pull-right'}
                                {$title='aktywny'}
                            {else}
                                {$class='fa fa-2 fa-eye-slash pull-right'}
                                {$title='ukryty'}
                            {/if}
                            {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'toggle'])}
                                <a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=toggle&widgetId={$widgetRelation->widget}&categoryId={$category->id}&id={$widgetRelation->id}@}" data-state="{$widgetRelation->active}" class="button toggle-widget" target="_blank" title="{$title}" id="widget-activate-{$widgetRelation->widget}"><i class="fa fa-eye pull-right fa-2 {$class}"></i></a>
                            {/if}
                            {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'sort'])}
                                <a href="#" class="button handle-widget" title="sortuj"><i class="fa fa-2 mr-fix-6  pull-right fa-sort"></i></a>
                            {/if}
                            {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'delete'])}
                                <a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&categoryId={$category->id}&uploaderId={$request->uploaderId}&originalId={$request->originalId}&id={$widgetRelation->id}@}" class="button confirm" title="usuń widget"><i class="fa fa-trash-o mr-fix-3 pull-right fa-2"></i></a>
                            {/if}
                        </div>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/foreach}