<h2 class="top-space">Top aktualności</h2>
<ul>
	{foreach $news as $entry}
		<li>
			<a href="{@module=cms&controller=news&action=display&uri={$entry->uri}@}">
				{$entry->title|truncate:45}
			</a>
		</li>
	{/foreach}
</ul>