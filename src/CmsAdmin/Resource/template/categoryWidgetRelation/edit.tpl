<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <h5>{$category->name} ({_($widgetModel->getTemplateConfig()->getName())})</h5>
                <div class="card mt-4">
                    <div class="card-header">
                        <strong>{_($widgetModel->getWidgetConfig()->getName())} - {_($widgetModel->getSectionConfig()->getName())}</strong>
                    </div>
                    <div class="card-body">
                        {$output}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>