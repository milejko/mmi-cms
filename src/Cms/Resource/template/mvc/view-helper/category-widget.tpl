{$_widgetData}
{if $auth && $auth->hasRole('admin')}
    <div style="display: none;" class="widget-panel">
        <button onclick="window.open('{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}');">Edycja</button>
        <button onclick="window.open('{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}');">Usuń</button>
    </div>
{/if}
