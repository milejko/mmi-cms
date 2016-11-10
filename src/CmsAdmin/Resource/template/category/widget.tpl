{foreach $widgetRelation->getAttributeValues() as $attributeValue}
	{* wartość złożona *}
	{if $attributeValue instanceof \Mmi\Orm\RecordCollection}
		{* iteracja po wartościach *}
		{foreach $attributeValue as $value}
			{* plik *}
			{if $value instanceof Cms\Orm\CmsFileRecord}
				<img src="{thumb($value, 'scalecrop', '100x100')}" alt="{$file->title}" />
			{* rekord *}
			{elseif $value instanceof Mmi\Orm\RecordRo}
				{$value|dump}
			{* skalar *}
			{else}
				{$value}
			{/if}
		{/foreach}
		<br />
		{continue}
	{/if}
	{$attributeValue}<br>
{/foreach}