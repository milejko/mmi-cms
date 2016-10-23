{foreach $files as $file}
	<li>
		<a class="insert" data-typ="{$file['class']}" href="{$file['full']}" title="{$file['title']}">&nbsp;</a>
		<a class="delete" data-file="{$file['title']}" data-id="{$file['id']}" href="#delete">Usuń</a>
				
		{if $file['class']=="image"}<img src="{$file['thumb']}" alt="" />{/if}
		{if $file['class']=="audio"}<a class="edit b_audio" data-id="{$file['id']}" href="#odtworz">Odtwórz</a><div class="thumbbox audio"><audio src="{$file['full']}"></audio></div>{/if}
		{if $file['class']=="video"}<a class="edit b_video" data-id="{$file['id']}" href="#odtworz">Odtwórz</a><div class="thumbbox video"><video src="{$file['full']}"></video></div>{/if}
	</li>	
{/foreach}