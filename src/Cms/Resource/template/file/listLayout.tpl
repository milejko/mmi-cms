{foreach $files as $file}
	<li>
		<a class="insert fa fa-plus-square" data-typ="{$file['class']}" href="{$file['full']}" title="dodaj"></a>
		<a class="delete fa fa-minus-square" data-file="{$file['title']}" data-id="{$file['id']}" title="usuń"></a>
				
		{if $file['class']=="image"}<div class="thumbbox image" style="background:url({$file['thumb']})"></div>{/if}
		{if $file['class']=="audio"}<a class="edit b_audio fa fa-volume-off" data-id="{$file['id']}" href="#odtworz"></a><div class="thumbbox audio"><audio src="{$file['full']}"></audio></div>{/if}
		{if $file['class']=="video"}<a class="edit b_video fa fa-play" data-id="{$file['id']}" href="#odtworz"></a><div class="thumbbox video"><video src="{$file['full']}" ></video></div>{/if}
	</li>	
{/foreach}