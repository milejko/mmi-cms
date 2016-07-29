<div class="content-box">
	<div class="content-box-header">
		<h3>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#wartości atrybutu#}</h3>
	</div>
	<div class="content-box-content clearfix">
		{$attributeValueForm}
	</div>
</div>