{foreach $sections as $section}
    {$widgetRelations = $widgetModel->getWidgetRelationsBySectionKey($section->getKey())}
    {$widgetsCount = $widgetRelations|count}
    <div id="{$section->getKey()}" class="card boxSection" style="margin-bottom: 15px">
        <div class="card-header">
            <strong>{_($section->getName())}</strong>
        </div>
        <div class="section-widgets card-body">
            <div class="available-widgets">
                <h6>{#template.categoryWidgetRelation.addWidget#}</h6>
                {foreach $section->getAvailableWidgets() as $availableWidgetKey => $availableWidget}
                    {if $widgetValidator->isWidgetAvailable($availableWidgetKey)}
                        <button id="{$section->getKey()}/{$availableWidget->getKey()}" class="button btn btn-primary btn-inline-block" type="submit" name="cmsadmin-form-categoryform[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&widget={$availableWidgetKey}&action=edit&categoryId={$category->id}&originalId={$category->cmsCategoryOriginalId}@}">
                            {_($availableWidget->getName())}
                        </button>
                    {else}
                        <button class="button btn btn-inline-block" disabled>
                            {_($availableWidget->getName())}
                        </button>
                    {/if}
                {/foreach}
                {if $widgetsCount > 0 && aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'sort'])}
                <h6 class="mt-3">
                    {* zwija sekcje do sortowania *}
                        {#template.categoryWidgetRelation.widgetList#}
                        <small class="ml-2">
                            <a class="show-all" href="#"><i class="fa fa-caret-down"></i> {#template.categoryWidgetRelation.showAll#}</i></a>
                            <a class="hide-all hidden" href="#"><i class="fa fa-caret-right"></i> {#template.categoryWidgetRelation.hideAll#}</i></a>
                        </small>
                </h6>
                {/if}
            </div>
            <a href="#" class="toggle-widgets"></a>
            <ul class="wlist ui-sortable widget-list" data-category-id="{$category->id}">
                {foreach $widgetRelations as $widgetRelation}
                    {$widgetContent = categoryWidgetPreview($widgetRelation)}
                    {foreach $section->getAvailableWidgets() as $widgetKey => $widget}
                        {if $widgetKey == $widgetRelation->widget}
                            {$widgetName = _($widget->getName())}
                        {/if}
                    {/foreach}
                    <li id="widget-item-{$widgetRelation->id}" class="ui-sortable-handle folded">
                        <div{if $widgetsCount > 1} class="handle-widget"{/if}>
                            <div class="sort-preview">
                                {if $widgetsCount > 1}{/if}<strong class="toogleWidget">{$widgetName}</strong>&nbsp;&nbsp;<i class="fa fa-caret-right"></i>
                            </div>
                            <div class="preview">
                                {if $widgetsCount > 1}{/if} <strong class="toogleWidget">{$widgetName}</strong>&nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                                <div>{$widgetContent}</div>
                            </div>
                            <div class="operation">
                                {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'config'])}
                                    <button class="button edit" type="submit" name="cmsadmin-form-categoryform[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&action=edit&widget={$widgetRelation->widget}&id={$widgetRelation->id}&categoryId={$category->id}&originalId={$category->cmsCategoryOriginalId}@}">
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
                        </div>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/foreach}
