{$value = $_element->getValue()}
{* translate *}
{$replacement = _($value)}
<input class="btn btn-primary float-right" type="submit" {$_htmlOptions|replace:$value:$replacement} />