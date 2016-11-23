{$attributes = $category->getAttributeValues()}

<h1>{$category->name}</h1>

{*listing atrybutów*}
<h2>Atrybuty:</h2>
{foreach $attributes as $attribute}
	{if $attribute instanceof \Mmi\Orm\RecordCollection}
		{foreach $attribute as $value}
			{if $value instanceof \Cms\Orm\CmsFileRecord}
			<img src="{thumb($value, 'scalecrop', '160x100')}" alt="" />
			{else}
				{$value}
			{/if}
		{/foreach}
	{else}
		{$attribute}
	{/if}
{/foreach}

{foreach $category->getWidgetModel()->getWidgetRelations() as $widgetRelation}
{if !$widgetRelation->active}{continue}{/if}
{$widgetRequest = $widgetRelation->getWidgetRecord()->getMvcParamsAsRequest()}
{$widgetName = $widgetRelation->getWidgetRecord()->name}
<div class="cms-widget widget-{$widgetRelation->getWidgetRecord()->id} widget-{$widgetName|url}">
	{widget($widgetRequest->module, $widgetRequest->controller, $widgetRequest->action, $widgetRequest->toArray() + ['widgetId' => $widgetRelation->id])}
</div>
{/foreach}