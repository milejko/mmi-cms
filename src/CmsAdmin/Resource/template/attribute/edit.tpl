<div class="content-box">
	<div class="content-box-header">
		<h3>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#atrybutu#}</h3>
	</div>
	<div class="content-box-content clearfix">
		{$attributeForm}
	</div>
</div>

{if $valueGrid}
<div class="content-box">
	<div class="content-box-header">
		<h3>{#Wartości atrybutu#}</h3>
	</div>
	<div class="content-box-content clearfix">
		<a class="button" href="{@module=cmsAdmin&controller=attributeValue&action=edit&cmsAttributeId={$attributeForm->getRecord()->id}@}">{#Utwórz nową wartość#}</a>
		<br /><br />
		{$valueGrid}
	</div>
</div>
{/if}