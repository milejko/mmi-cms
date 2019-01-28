{headLink()->appendStylesheet('/resource/cmsAdmin/js/datetimepicker/jquery.datetimepicker.min.css')}
{headScript()->appendFile('/resource/cmsAdmin/js/datetimepicker/jquery.datetimepicker.full.min.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/chosen.jquery.min.js')}
{headScript()->appendFile('/resource/cmsAdmin/js/grid.js')}
<div class="grid">
    <div class="row">
        <div class="col-12">
            <table id="{$_grid->getClass()}" class="table table-striped grid-anchor {if $_grid->getOption('sortableAction')}table-sort{/if}"
                   {if $_grid->getOption('sortableAction')}data-sort-url="{$_grid->getOption('sortableAction')}"{/if}>
                {$_renderer->renderHeader()}
                {$_renderer->renderBody()}
            </table>
        </div>
    </div>
    <div class="row">
        <div id="{$_grid->getClass()}-paginator" class="col-12 paginator-anchor">
            {$_renderer->renderFooter()}
        </div>
    </div>
</div>
