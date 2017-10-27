<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if $request->id > 0}{#Edycja#}{else}{#Dodawanie#}{/if} {#szablonu maila#}</strong>
                    </div>
                    <div class="card-body">
                        {$definitionForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
