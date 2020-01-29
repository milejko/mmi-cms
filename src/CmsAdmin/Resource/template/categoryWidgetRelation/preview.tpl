{if $sections|count}
    {foreach $sections as $section}
        {$sectionId = $section->id} {* potrzebne w poniższym partialu *}
        {'cmsAdmin/categoryWidgetRelation/partial/section'}
    {/foreach}
    {$widgetRelations = $category->getWidgetModel()->getWidgetRelationsBySectionId(null)}
    {if $widgetRelations|count}
        <p>Posiadasz {$widgetRelations|count} widgetów nie przypisanych do żadnej sekcji</p>
    {/if}
{else}
    {if aclAllowed(['module' => 'cmsAdmin', 'controller' => 'categoryWidgetRelation', 'action' => 'add'])}
        <div style="overflow-x: auto; white-space:nowrap;">
            {foreach $availableWidgets as $availableWidget}
                <button id="cmsadmin-form-category-submit" type="submit" class="button btn btn-primary btn-inline-block" name="cmsadmin-form-category[submit]" value="redirect:{@module=cmsAdmin&controller=categoryWidgetRelation&widgetId={$availableWidget->id}&action=config&categoryId={$category->id}&uploaderId={$request->uploaderId}&originalId={$category->cmsCategoryOriginalId}@}">
                    <i class="icon-plus"></i> {$availableWidget->name}
                </button>
            {/foreach}
        </div>
    {/if}
    {$widgetRelations = $category->getWidgetModel()->getWidgetRelations()} {* potrzebne w poniższym partialu *}
    {$sectionId = null} {* potrzebne w poniższym partialu *}
    {'cmsAdmin/categoryWidgetRelation/partial/widgets'}
{/if}