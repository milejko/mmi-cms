<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.config.index.header#}</strong>
                    </div>
                    <div class="card-body">
                        <pre>{$config}</pre>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <strong>{#template.config.index.server.header#} @ PHP {php_phpversion()} {php_php_sapi_name()}</strong>
                    </div>
                    <div class="card-body">
                        <pre>
                        {$server}
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>