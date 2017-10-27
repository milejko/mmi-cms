<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#tematu kontaktu#}</strong>
                    </div>
                    <div class="card-body">
                        {$optionForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
