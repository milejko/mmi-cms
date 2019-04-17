{if $_column->isFieldInRecord()}
    <a class="order" href="#{$_column->getFormColumnName()}" data-method="{$_column->getOrderMethod()}">
{/if}
{$_column->getLabel() ? _($_column->getLabel()) : $_column->getName()}
{if $_column->isFieldInRecord()}
    &nbsp;
    {if 'orderDesc' == $_column->getOrderMethod()}
        <i class="fa fa-sort-desc" style="color: #20a8d8"></i></a>
    {elseif 'orderAsc' == $_column->getOrderMethod()}
        <i class="fa fa-sort-asc" style="color: #20a8d8"></i></a>
    {elseif !$_column->getOrderMethod()}
        <i class="fa fa-sort" style="color: #20a8d8"></i></a>
    {/if}
{/if}