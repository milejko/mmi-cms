<h1>{$category->name}</h1>
{$category->text}

{foreach $attributes as $av}
	{$av->getJoined('cms_attribute')->name}: {$av->value}<br>
{/foreach}