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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.categoryType.edit.attributes#}</strong>
                    </div>
                    <div class="card-body">
                        {if $request->id}
                            <a href="{@module=cmsAdmin&controller=categoryTypeAttribute&action=edit&categoryTypeId={$request->id}@}" class="btn btn-primary"><i class="icon-plus"></i> {#template.categoryType.edit.attribute.new#}</a>
                        {/if}
                        {$relationGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.categoryType.edit.sections#}</strong>
                    </div>
                    <div class="card-body">
                        {if $request->id}
                            <a href="{@module=cmsAdmin&controller=categorySection&action=edit&categoryTypeId={$request->id}@}" class="btn btn-primary"><i class="icon-plus"></i> {#template.categoryType.edit.section.new#}</a>
                        {/if}
                        {$sectionGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>