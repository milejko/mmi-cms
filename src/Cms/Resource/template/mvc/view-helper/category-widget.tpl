{$_widgetData}
{if $auth && $auth->hasRole('admin')}
    <div class="widget-panel">
        {$draft = false}
        {if $_widgetRelation->getJoined('cms_category')->cmsCategoryOriginalId}
            {$draft = true}
        {/if}
        <div class="widget-btn" onclick="redirectToCMS('{if $draft}{url(['module' => 'cmsAdmin', 'controller' => 'category', 'action' => 'edit', 'id' => $_widgetRelation->cmsCategoryId, 'originalId' => $_widgetRelation->getJoined('cms_category')->cmsCategoryOriginalId], true)}{else}{url(['module' => 'cmsAdmin', 'controller' => 'category', 'action' => 'edit', 'id' => $_widgetRelation->cmsCategoryId, 'force' => 1], true)}{/if}', this, '{$_widgetRelation->id}')">
            <img class="img-btn" src="/resource/cms/js/widget/cms.png"/>
        </div>
    </div>
    {headLink()->appendStyleSheet('/resource/cms/js/widget/widget.css')}
    {headScript()->appendFile('/resource/cms/js/widget/widgets.js')}
{/if}
