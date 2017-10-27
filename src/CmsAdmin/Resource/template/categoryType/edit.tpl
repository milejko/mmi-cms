<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#szablonu#}</strong>
                    </div>
                    <div class="card-body">
                        {$categoryTypeForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{if $relationGrid}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->relationId}{#Dodaj atrybut#}{else}{#Edytuj opcje atrybutu#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$relationForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Atrybuty#}</strong>
                    </div>
                    <div class="card-body">
                        {$relationGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}