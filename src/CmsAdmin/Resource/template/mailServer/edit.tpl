<div class="content-box">
	<div class="content-box-header">
		<h3>{if $request->id > 0}{#Edycja#}{else}{#Dodawanie#}{/if} {#serwera poczty#}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content clearfix">
		{$serverForm}
	</div>
</div>