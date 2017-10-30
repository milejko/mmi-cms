{*if $auth && $auth->hasRole('admin')}
<div class="widget-panel">
    <a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}">Edycja</a>
    <a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&id={$_widgetRelation->id}&categoryId={$_widgetRelation->cmsCategoryId}&widgetId={$_widgetRelation->cmsCategoryWidgetId}@}">Usuń</a>
</div>
{/if*}
{$_widgetData}