<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card p-4">
                    <div class="card-body">
                        <h1>{#Zaloguj się#}</h1>
                        <p class="text-muted"><span>{$domain|replace:'www.':''}</span></p>
                        {if($loginForm)}
                        {$loginForm}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>