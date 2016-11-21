<div class="content-box">
	<div class="content-box-header">
		<h3>{if $request->id > 0}{#Edycja#}{else}{#Dodawanie#}{/if} {#konfiguracji widgeta#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		{$widgetForm}
	</div>
</div>

{if $relationGrid}
	<div class="content-box">
		<div class="content-box-header">
			<h3>{if !$request->relationId}{#Dodaj atrybut#}{else}{#Edytuj opcje atrybutu#}{/if}</h3>
			<div class="clear"></div>
		</div>
		<div class="content-box-content clearfix">
			{$relationForm}
		</div>
	</div>
	<div class="content-box">
		<div class="content-box-header">
			<h3>{#Atrybuty#}</h3>
			<div class="clear"></div>
		</div>
		<div class="content-box-content clearfix">
			{$relationGrid}
		</div>
	</div>
{/if}