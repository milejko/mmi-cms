{foreach $attributes as $av}
	{* obrazy / pliki *}
	{if $av instanceof \Mmi\Orm\RecordCollection}
		{foreach $av as $file}
			{if $file->class == 'image'}
				<img src="{thumb($file, 'scalecrop', '100x100')}" alt="{$file->title}" />
			{else}
				<a href="{$file->getUrl()}">{$file->title}</a>
			{/if}
		{/foreach}
		<br />
		{continue}
	{/if}
	{$av->getJoined('cms_attribute')->name}: {$av->value}<br>
{/foreach}