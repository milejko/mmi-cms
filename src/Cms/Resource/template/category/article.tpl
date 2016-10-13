{$category = $widgetModel->getCategoryRecord()}

<h1>{$category->name}</h1>
{$category->text}

<h2>Atrybuty:</h2>
{foreach $attributes as $av}
	{$av->getJoined('cms_attribute')->name}: {$av->value}<br>
{/foreach}

<h2>Tagi:</h2>
{foreach $tags as $tag}
	{$tag}
{/foreach}

{foreach $widgetModel->getWidgetRelations() as $widgetRelation}
{if !$widgetRelation->active}{continue}{/if}
{$widgetRequest = $widgetRelation->getWidgetRecord()->getMvcParamsAsRequest()}
{$widgetName = $widgetRelation->getWidgetRecord()->name}
<div class="cms-widget widget-{$widgetRelation->getWidgetRecord()->id} widget-{$widgetName|url}">
	{widget($widgetRequest->module, $widgetRequest->controller, $widgetRequest->action, $widgetRequest->toArray() + ['widgetId' => $widgetRelation->id])}
</div>
{/foreach}