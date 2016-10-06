{$categoryId = $request->categoryId}
<ul class="list ui-sortable" id="widget-list" data-category-id="{$categoryId}">
	{foreach $widgetModel->getWidgetRelations() as $widgetRelation}
	<li id="widget-item-{$widgetRelation->id}" class="ui-sortable-handle">
		<div class="preview">
			{$widgetPreviewRequest = $widgetRelation->getWidgetRecord()->getMvcPreviewParamsAsRequest()}
			{widget($widgetPreviewRequest->module, $widgetPreviewRequest->controller, $widgetPreviewRequest->action, $widgetPreviewRequest->toArray() + ['widgetId' => $widgetRelation->id])}
		</div>
		<div class="operation">
			<a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&widgetId={$widgetRelation->getWidgetRecord()->id}&categoryId={$categoryId}&id={$widgetRelation->id}@}" class="button new-window edit" target="_blank" title="zmień widget"><i class="icon-edit"></i></a><br />
			<a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&widgetId={$widgetRelation->getWidgetRecord()->id}&categoryId={$categoryId}&id={$widgetRelation->id}@}" class="button new-window edit" target="_blank" title="pokaż/ukryj"><i class="icon-eye-open"></i></a><br />
			<a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&categoryId={$categoryId}&id={$widgetRelation->id}@}" class="button delete-widget" target="_blank" title="usuń widget"><i class="icon-remove-sign"></i></a>
		</div>
	</li>
	{/foreach}
</ul>
