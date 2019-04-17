<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if $request->id > 0}{#template.categoryWidget.edit.header.new#}{else}{#template.categoryWidget.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$widgetForm}
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
                        <strong>{if !$request->relationId}{#template.categoryWidget.edit.attribute.new#}{else}{#template.categoryWidget.edit.attribute.edit#}{/if}</strong>
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
                        <strong>{#template.categoryWidget.edit.attributes#}</strong>
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