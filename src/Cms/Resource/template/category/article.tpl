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
	{$widgetRequest = $widgetRelation->getWidgetRecord()->getMvcParamsAsRequest()}
	{widget($widgetRequest->module, $widgetRequest->controller, $widgetRequest->action, $widgetRequest->toArray() + ['widgetId' => $widgetRelation->id])}
{/foreach}