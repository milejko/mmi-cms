<h1>{#Aktualności#}</h1>
{foreach $news as $item}
	<div class="post">
		{$image = $item->getFirstImage()}
		{if $image}
			<a href="{if $item->internal}{@module=cms&controller=news&action=display&uri={$item->uri}@}{else}{$item->uri}{/if}">
				<img src="{thumb($image, 'scalecrop', '160x120')}" alt="{$item->title}" />
			</a>
		{/if}
		<h3>
			<a href="{if $item->internal}{@module=cms&controller=news&action=display&uri={$item->uri}@}{else}{$item->uri}{/if}">
				{$item->title}
			</a>
		</h3>
		<h4>
			{$item->dateAdd|dateFormat}
		</h4>
		{if $item->lead}
			{$item->lead}
		{else}
			{$item->text}
		{/if}
	</div>
{/foreach}
{$paginator}