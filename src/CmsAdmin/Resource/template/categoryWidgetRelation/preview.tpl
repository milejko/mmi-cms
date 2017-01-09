<ul class="list ui-sortable" id="widget-list" data-category-id="{$category->id}">
	{foreach $category->getWidgetModel()->getWidgetRelations() as $widgetRelation}
	<li id="widget-item-{$widgetRelation->id}" class="ui-sortable-handle">
		<div class="preview">
			{$widgetPreviewRequest = $widgetRelation->getWidgetRecord()->getMvcPreviewParamsAsRequest()}
			{widget($widgetPreviewRequest->module, $widgetPreviewRequest->controller, $widgetPreviewRequest->action, $widgetPreviewRequest->toArray() + ['widgetId' => $widgetRelation->id])}
		</div>
		<div class="operation">
			<a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=config&widgetId={$widgetRelation->getWidgetRecord()->id}&categoryId={$category->id}&id={$widgetRelation->id}@}" class="button new-window edit" target="_blank" title="zmień widget"><i class="icon-edit"></i></a><br />
			{if $widgetRelation->active == 1}
				{$class='open'}
				{$title='aktywny'}
			{elseif $widgetRelation->active == 2}
				{$class='open red'}
				{$title='roboczy'}
			{else}
				{$class='close'}
				{$title='ukryty'}
			{/if}
			<a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=toggle&widgetId={$widgetRelation->getWidgetRecord()->id}&categoryId={$category->id}&id={$widgetRelation->id}@}" data-state="{$widgetRelation->active}" class="button toggle-widget" target="_blank" title="{$title}"><i class="icon-eye-{$class}"></i></a><br />
			<a href="#" class="button handle-widget" title="sortuj"><i class="icon-sort"></i></a><br />
			<a href="{@module=cmsAdmin&controller=categoryWidgetRelation&action=delete&categoryId={$category->id}&id={$widgetRelation->id}@}" class="button delete-widget" target="_blank" title="usuń widget"><i class="icon-remove-sign"></i></a>
		</div>
	</li>
	{/foreach}
</ul>
