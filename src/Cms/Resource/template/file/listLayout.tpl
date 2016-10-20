{foreach $files as $file}
	<li>
		<a class="insert" href="{$file['full']}" title="{$file['title']}">&nbsp;</a>
		<img src="{$file['thumb']}" alt="" />
	</li>	
{/foreach}