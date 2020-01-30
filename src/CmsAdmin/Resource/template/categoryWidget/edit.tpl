<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if $request->id}{#template.categoryWidget.edit.header.new#}{else}{#template.categoryWidget.edit.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$widgetForm}
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
                        {if $request->id}
                            <a href="{@module=cmsAdmin&controller=categoryWidgetAttribute&action=edit&categoryWidgetId={$request->id}@}" class="btn btn-primary"><i class="icon-plus"></i> {#template.categoryWidget.edit.attribute.new#}</a>
                        {/if}
                        {$relationGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>