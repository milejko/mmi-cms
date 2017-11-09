{$_widgetData}
{if $auth && $auth->hasRole('admin')}
    <div class="widget-panel">
        <div class="widget-btn first" onclick="editWidget('{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}')" data-link="">
            <img src="/resource/cms/js/widget/edit.png"/>
        </div>
        <div class="widget-btn" onclick="deleteWidget('{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}', this)">
            <img src="/resource/cms/js/widget/trashpng.png"/>
        </div>
    </div>
    {headLink()->appendStyleSheet('/resource/cms/js/widget/widget.css')}
    {headScript()->appendFile('/resource/cms/js/widget/widgets.js')}
{/if}
