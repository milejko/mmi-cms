{$_widgetData}
{if $auth && $auth->hasRole('admin')}
    <div class="widget-panel">
        <div class="widget-btn first" onclick="editWidget('{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}')" data-link="">
            <img class="img-btn" src="/resource/cms/js/widget/edit.png"/>
        </div>
        <div class="widget-btn" onclick="redirectToCMS(''), this)">
            <img class="img-btn" src="/resource/cms/js/widget/cms.png"/>
        </div>
    </div>
    {headLink()->appendStyleSheet('/resource/cms/js/widget/widget.css')}
    {headScript()->appendFile('/resource/cms/js/widget/widgets.js')}
{/if}
