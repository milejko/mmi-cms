{headScript()->appendFile('/resource/cmsAdmin/js/grid.js')}
<div id="{$_grid->getClass()}-container">
    <div class="row">
        <div id="{$_grid->getClass()}" class="col-12">
            <table class="table table-striped">
                {$_renderer->renderHeader()}
                {$_renderer->renderBody()}
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            {$_renderer->renderFooter()}
        </div>
    </div>
</div>