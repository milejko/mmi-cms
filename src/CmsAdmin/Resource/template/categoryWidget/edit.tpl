<div class="content-box">
	<div class="content-box-header">
		<h3>{if $request->id > 0}{#Edycja#}{else}{#Dodawanie#}{/if} {#konfiguracji widgeta#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		{$widgetForm}
	</div>
</div>