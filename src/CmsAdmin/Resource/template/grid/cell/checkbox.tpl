<label class="control control-checkbox">
    <input type="checkbox" name="{$_column->getFormColumnName()}" class="field checkbox" value="{$_column->getCheckedValue()}" id="{$_column->getFormColumnName()}-{$_record->id}"{if $_column->getOption('disabled')} disabled{/if}{if $_column->getCheckedValue() <= $_value} checked{/if} />
    <div class="control_indicator"></div>
</label>
