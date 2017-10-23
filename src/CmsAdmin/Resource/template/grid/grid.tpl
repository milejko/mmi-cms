{headScript()->appendFile('/resource/cmsAdmin/js/grid.js')}
<div class="row">
    <div class="col-12">
        <table id="{$_grid->getClass()}" class="table table-striped">
            {$_renderer->renderHeader()}
            {$_renderer->renderBody()}
        </table>
    </div>
</div>
<div class="row">
    <div id="{$_grid->getClass()}-paginator" class="col-12">
        {$_renderer->renderFooter()}
    </div>
</div>
