<div class="content-box">
	<div class="content-box-header">
		<h3>{#Treści widgetu Text#}</h3>
		<div class="clear"></div>
	</div>
	{if $request->id}Edytujesz tekst o ID = {$textId}{/if}
	<div class="content-box-content clearfix">
		{$textForm}
	</div>
	<div class="content-box-content clearfix">
		Dostępne treści:
		{$grid}
	</div>
</div>