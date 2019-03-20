<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#template.tagRelation.edit.header.new#}{else}{#template.tagRelation.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$tagRelationForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
