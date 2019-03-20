<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#template.attributeGroup.edit.header.new#}{else}{#template.attributeGroup.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$attributeGroupForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>