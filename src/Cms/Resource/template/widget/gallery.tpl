{foreach $images as $image}
	<img src="{thumb($image, 'scaley', 200)}" alt="{$image->title}">
{/foreach}