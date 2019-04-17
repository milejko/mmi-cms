<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->id}{#template.contact.editSubject.header.new#}{else}{#template.contact.editSubject.header.edit#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$optionForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
