<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#Dodawanie#}{else}{#Edycja#}{/if} {#tagu#}</strong>
                    </div>
                    <div class="card-body">
                        {$tagForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
