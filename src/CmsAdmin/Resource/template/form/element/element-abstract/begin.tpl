{$class = php_get_class($_element)}
<div class="form-group {$class|lowercase|replace:'\\':''}" {if $_element->getId()}id="{$_element->getId()}-container"{/if}>
