{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.category.tree.header#}</strong>
                        <span id="categoryMessageContainer" style="display: inline"></span>
                    </div>
                    <div class="card-body">                        
                        <div id="categoryTreeContainer">
                            <div id="jstree" data-url="{@module=cmsAdmin&controller=category&action=node@}">
                                {jsTree([], $baseUrl . '/resource/cmsAdmin/js/tree.js')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
