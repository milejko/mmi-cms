<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#template.categoryType.edit.header.new#}{else}{#template.categoryType.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$categoryTypeForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{if $relationGrid}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->relationId}{#template.categoryType.edit.attribute.new#}{else}{#template.categoryType.edit.attribute.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$relationForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.categoryType.edit.attributes#}</strong>
                    </div>
                    <div class="card-body">
                        {$relationGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}