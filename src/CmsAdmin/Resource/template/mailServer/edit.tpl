<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if $request->id > 0}{#template.mailServer.edit.header.new#}{else}{#template.mailServer.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$serverForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
