<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#nazwy statystyki#}</strong>
                    </div>
                    <div class="card-body">
                        {$labelForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
