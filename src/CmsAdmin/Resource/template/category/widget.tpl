<ul class="list ui-sortable" id="widget-list" data-category-id="{$request->id}">
	{foreach $widgetModel->getWidgetCollection() as $configuredWidget}
	<li id="widget-item-{$configuredWidget->id}" class="ui-sortable-handle">
		<div>
			{$widgetPreviewRequest = $configuredWidget->getWidgetRecord()->getMvcPreviewParamsAsRequest()}
			{widget($widgetPreviewRequest->module, $widgetPreviewRequest->controller, $widgetPreviewRequest->action, $widgetPreviewRequest->toArray() + ['widgetId' => $configuredWidget->id])}
		</div>
		<div>
			<a href="{@module=cmsAdmin&controller=categoryConfig&action=config&widgetId={$configuredWidget->getWidgetRecord()->id}&categoryId={$request->id}&id={$configuredWidget->id}@}" class="button new-window edit" target="_blank"><i class="icon-edit"></i> edytuj</a>
			<a href="{@module=cmsAdmin&controller=categoryConfig&action=delete&categoryId={$request->id}&id={$configuredWidget->id}@}" class="confirm button new-window" target="_blank" title="Czy na pewno usunąć widget?"><i class="icon-remove-sign"></i> usuń</a>
		</div>
	</li>
	{/foreach}
</ul>
