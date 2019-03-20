<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#template.attribute.edit.header.new#}{else}{#template.attribute.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$attributeForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{if $valueForm}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.attribute.edit.value.new#}</strong>
                    </div>
                    <div class="card-body">
                        {$valueForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
{if $valueGrid}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.attribute.edit.values#}</strong>
                    </div>
                    <div class="card-body">
                        {$valueGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
