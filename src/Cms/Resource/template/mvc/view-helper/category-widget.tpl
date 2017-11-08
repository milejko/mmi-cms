{$_widgetData}
{if $auth && $auth->hasRole('admin')}
    <div class="widget-panel">
        <div class="widget-btn first" onclick="editWidget('{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}')" data-link="">
            <i class="fa fa-2 fa-pencil-square-o" aria-hidden="true"></i>
        </div>
        <div class="widget-btn" onclick="deleteWidget('{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}', this)">
            <i class="fa fa-2 fa-trash-o" aria-hidden="true"></i>
        </div>
    </div>
    {headLink()->appendStyleSheet('/resource/cms/js/widget/widget.css')}
    {headLink()->appendStyleSheet('/resource/cmsAdmin/vendors/css/font-awesome.min.css')}
    {headScript()->appendFile('/resource/cms/js/widget/widgets.js')}
{/if}
