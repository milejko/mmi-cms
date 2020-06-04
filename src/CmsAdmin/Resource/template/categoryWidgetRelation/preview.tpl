{foreach $sections as $section}
    {$widgetRelations = $category->getWidgetModel()->getWidgetRelationsBySectionKey($section->getKey())}
    {$widgetsCount = $widgetRelations|count}
    <div class="card boxSection" style="margin-bottom: 15px">
        <div class="card-header">
            <strong>{_($section->getName())}</strong>
            {* zwija sekcje do sortowania *}
            {if $widgetsCount > 1 && aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'sort'])}
                <small class="ml-2">
                    <a class="fold" href="#"><i class="icon-layers"></i> {#template.categoryWidgetRelation.fold#}</i></a>
                    <a class="unfold" href="#"><i class="icon-layers"></i> {#template.categoryWidgetRelation.unfold#}</i></a>
                </small>
            {/if}
        </div>
        <div class="section-widgets card-body">
            <div class="available-widgets" style="overflow-x: auto; white-space:nowrap;">
                {foreach $section->getAvailableWidgets() as $availableWidgetKey => $availableWidget}
                    {if $widgetValidator->isWidgetAvailable($availableWidgetKey)}
                        <button class="button btn btn-primary btn-inline-block" type="submit" name="cmsadmin-form-categoryform[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&widget={$availableWidgetKey}&action=edit&categoryId={$category->id}&originalId={$category->cmsCategoryOriginalId}@}">
                            <i class="icon-plus"></i> {_($availableWidget->getName())}
                        </button>
                    {else}
                        <button class="button btn btn-inline-block" disabled>
                            <i class="icon-plus"></i> {_($availableWidget->getName())}
                        </button>
                    {/if}
                {/foreach}
            </div>
            <ul class="wlist ui-sortable widget-list" data-category-id="{$category->id}">
                {foreach $widgetRelations as $widgetRelation}
                    {$widgetContent = categoryWidgetPreview($widgetRelation)}
                    <li id="widget-item-{$widgetRelation->id}" class="ui-sortable-handle">
                        <div class="sort-preview handle-widget">
                            {foreach $section->getAvailableWidgets() as $widgetKey => $widget}
                                {if $widgetKey == $widgetRelation->widget}
                                    <i class="fa fa2 fa-sort"></i> <strong>{_($widget->getName())}</strong> / {$widgetContent|stripTags|truncate:100}
                                {/if}
                            {/foreach}
                        </div>
                        <div class="preview">
                            {$widgetContent}
                        </div>
                        <div class="operation">
                            {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'config'])}
                                <button class="button edit" type="submit" name="cmsadmin-form-categoryform[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&action=edit&widget={$widgetRelation->widget}&id={$widgetRelation->id}&categoryId={$category->id}&uploaderId={$widgetRelation->id}&originalId={$category->cmsCategoryOriginalId}@}">
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
                            {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'delete'])}
                                <a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&categoryId={$category->id}&originalId={$request->originalId}&id={$widgetRelation->id}@}" class="button confirm" title="usuń widget"><i class="fa fa-trash-o mr-fix-3 pull-right fa-2"></i></a>
                            {/if}
                        </div>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/foreach}