{foreach $attributes as $av}
	{$av->getJoined('cms_attribute')->name}: {$av->value}<br>
{/foreach}