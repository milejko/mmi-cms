<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#template.categoryTypeAttribute.edit.header.new#}{else}{#template.categoryTypeAttribute.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$form}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>