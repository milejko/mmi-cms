<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Import plików#}</strong>
                    </div>
                    <div class="card-body">
                        {$form}
                        {if $files}
                        <div class="auto-download list-group">
                            {foreach $files as $file}
                                <a href="#" data-name="{$file.name}" class="list-group-item list-group-item-action">% {$file.object}/{$file.objectId}/{$file.original}</a>
                            {/foreach}
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('div.auto-download > a').each(function () {
            var obj = $(this);
            $.get(request.baseUrl + '/?module=cms&controller=connector&action=importFile&name=' + obj.attr('data-name')).always(function () {
                obj.children('span').html('100');
            });
        });
    });
</script>