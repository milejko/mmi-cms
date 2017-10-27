<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#atrybutu#}</strong>
                    </div>
                    <div class="card-body">
                        {$attributeForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{if $valueForm}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Nowa wartość atrybutu#}</strong>
                    </div>
                    <div class="card-body">
                        {$valueForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
{if $valueGrid}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Wartości atrybutu#}</strong>
                    </div>
                    <div class="card-body">
                        {$valueGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
