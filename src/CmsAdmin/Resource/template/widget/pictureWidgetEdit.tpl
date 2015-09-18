<div class="content-box">
	<div class="content-box-header">
		<h3>{#Dodaj zdjęcie widgetu Picture#}</h3>
		<div class="clear"></div>
	</div>
	{if $request->id}Edytujesz zdjęcie o ID = {$pictureId}{/if}
	<div class="content-box-content clearfix">
		{$pictureForm}
	</div>
	<div class="content-box-content clearfix">
		{$grid}
	</div>
</div>