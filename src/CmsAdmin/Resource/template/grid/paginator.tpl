{* ustawianie zmiennych *}
{$_showPages = 10}
{$_halfPages = php_floor($_showPages / 2)}
{$_page = $_grid->getState()->getPage()}
{$_pagesCount = $_paginator->getPagesCount()}
{$_previousLabel = 'Prev'}
{$_nextLabel = 'Next'}

{if 1 == $_pagesCount}{return}{/if}
<ul class="pagination" data-name="{$_grid->getClass()}[_paginator_]">
    {* pierwsza strona *}
    {if $_page > 1}
        {$_firstPage = (($_page - 1) > 1) ? ($_page - 1) : null}
        <li class="page-item previous"><a class="page-link" data-page="{$_firstPage}" href="#">{$_previousLabel}</a>
        </li>
    {/if}

    {* generowanie strony pierwszej *}
    {if 1 == $_page}
        <li class="page-item active"><a class="page-link" data-page="1" href="#">1</a></li>
    {else}
        <li class="page-item"><a class="page-link" data-page="1" href="#">1</a></li>
    {/if}

    {* obliczanie zakresów *}
    {$_rangeBegin = (($_page - $_halfPages) > 2) ? ($_page - $_halfPages) : 2}
    {$_rangeBeginExcess = $_halfPages - ($_page - 2)}
    {$_rangeBeginExcess = ($_rangeBeginExcess > 0) ? $_rangeBeginExcess : 0}

    {$_rangeEnd = (($_page + $_halfPages) < $_pagesCount) ? ($_page + $_halfPages) : $_pagesCount - 1}
    {$_rangeEndExcess = $_halfPages - ($_pagesCount - $_page - 1)}
    {$_rangeEndExcess = ($_rangeEndExcess > 0) ? $_rangeEndExcess : 0}

    {$_rangeEnd = (($_rangeEnd + $_rangeBeginExcess) < $_pagesCount) ? ($_rangeEnd + $_rangeBeginExcess) : $_pagesCount - 1}
    {$_rangeBegin = (($_rangeBegin - $_rangeEndExcess) > 2) ? ($_rangeBegin - $_rangeEndExcess) : 2}

    {* pierwsza strona w zakresie *}
    {if $_rangeBegin > 2}
        <li class="page-item">
            <a data-page="{php_floor((1 + $_rangeBegin) / 2)}" href="#">...</a>
        </li>
    {/if}

    {* generowanie stron w zakresie *}
    {for $_i = $_rangeBegin; $_i <= $_rangeEnd; $_i++}
        {if $_i == $_page}
            <li class="page-item active"><a class="page-link" data-page="{$_i}" href="#">{$_i}</a></li>
        {else}
            <li class="page-item"><a class="page-link" data-page="{$_i}" href="#">{$_i}</a></li>
        {/if}
    {/for}

    {* ostatnia strona w zakresie *}
    {if $_rangeEnd < $_pagesCount - 1}
        <li class="page-item dots"><a class="page-link" data-page="{php_ceil(($_rangeEnd + $_pagesCount) / 2)}" href="#">...</a></li>
    {/if}

    {* ostatnia strona w ogóle *}
    {if $_pagesCount == $_page}
        <li class="page-item last active"><a class="page-link" data-page="{$_i}" href="#">{$_pagesCount}</a></li>
    {else}
        <li class="page-item last page"><a class="page-link" data-page="{$_pagesCount}" href="#">{$_pagesCount}</a></li>
    {/if}

    {* generowanie guzika następny *}
    {if $_page < $_pagesCount}
        <li class="page-item next"><a class="page-link" data-page="{$_page + 1}" href="#">{$_nextLabel}</a></li>
    {else}
        <li class="page-item next"><a class="page-link" data-page="{$_page + 1}" href="#">{$_nextLabel}</a></li>
    {/if}
</ul>
