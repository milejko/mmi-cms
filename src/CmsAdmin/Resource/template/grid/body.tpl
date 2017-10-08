{foreach $_grid->getDataCollection() as $record}
    <tr>
        {*iteracja po Columnach*}
        {foreach $_grid->getColumns() as $column}
            {*renderuje krotkę*}
            <td>{$column->renderCell($record)}</td>
        {/foreach}
    </tr>
{/foreach}
