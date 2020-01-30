<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->relationId}{#template.categoryWidgetAttribute.edit.header.new#}{else}{#template.categoryWidgetAttribute.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$relationForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>