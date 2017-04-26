<div class="content-box">
    <div class="content-box-header">
        <h3>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#atrybutu#}</h3>
    </div>
    <div class="content-box-content clearfix">
        {$attributeForm}
    </div>
</div>

{if $valueForm}
    <div class="content-box">
        <div class="content-box-header">
            <h3>{#Nowa wartość atrybutu#}</h3>
        </div>
        <div class="content-box-content clearfix">
            {$valueForm}
        </div>
    </div>
{/if}

{if $valueGrid}
    <div class="content-box">
        <div class="content-box-header">
            <h3>{#Wartości atrybutu#}</h3>
        </div>
        <div class="content-box-content clearfix">
            {$valueGrid}
        </div>
    </div>
{/if}