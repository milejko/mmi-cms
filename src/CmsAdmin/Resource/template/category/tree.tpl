{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div id="categoryMessageContainer"></div>
                    <div class="card-header">
                        <strong>{#template.category.tree.header#}</strong>
                    </div>
                    <div class="card-body">
                       <div id="categoryTreeContainer">
                            <div id="jstree">
                                {jsTree([], $baseUrl . '/resource/cmsAdmin/js/tree.js')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
