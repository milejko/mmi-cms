<h1>{$item->title}</h1>
<div class="post">
	<h3>{$item->dateAdd|dateFormat:'d.m.Y'}</h3>
	{$image = $item->getFirstImage()}
	{if $image}
		<img src="{thumb($image, 'scalecrop', '300x200')}" alt="{$item->title}" />
	{/if}
	{if $item->lead}
		{$item->lead}
	{/if}
	{$item->text}
</div>

<a name="comments"></a>
{widget('cms', 'comment', 'index', array('allowGuests' => true, 'object' => 'news', 'objectId' => $item->id))}