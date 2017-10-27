<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if $request->id > 0}{#Edycja#}{else}{#Dodawanie#}{/if} {#zadania CRON#}</strong>
                    </div>
                    <div class="card-body">
                        {$cronForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>