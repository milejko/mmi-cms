<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#atrybutu w szablonine#}</strong>
                    </div>
                    <div class="card-body">
                        {$categoryTypeAttributeForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>