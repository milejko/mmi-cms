{$_widgetData}
{if $auth && $auth->hasRole('admin')}
    <div class="widget-panel">
        <div class="widget-btn" onclick="redirectToCMS('{@module=cmsAdmin&controller=category&action=edit&id={$_widgetRelation->cmsCategoryId}@}', this, '{$_widgetRelation->id}')">
            <img class="img-btn" src="/resource/cms/js/widget/cms.png"/>
        </div>
    </div>
    {headLink()->appendStyleSheet('/resource/cms/js/widget/widget.css')}
    {headScript()->appendFile('/resource/cms/js/widget/widgets.js')}
{/if}
