function showTooltip(x, y, contents) {
	$('<div id="tooltip">' + contents + '</div>').css({
		position: 'absolute',
		top: y - 24,
		left: x + 4,
		border: '1px solid #fdd',
		padding: '2px',
		'background-color': '#ffffcc',
		opacity: 0.80
	}).appendTo("body").fadeIn(200);
}

var handleTooltip = function handleTooltip(event, pos, item, ticks, index) {
	$("#x").text(pos.x.toFixed(2));
	$("#y").text(pos.y.toFixed(2));
	if (item) {
		$("#tooltip").remove();
		var x = item.datapoint[0].toFixed(2), y = item.datapoint[1].toFixed(2);
		if (Math.round(y) == y) {
			y = Math.round(y);
		}
		x = Math.round(x);
		showTooltip(item.pageX, item.pageY, ticks[x - 1] + ' (<strong>' + y + '</strong>)');
	}
}

//zmiana pola w formularzu statystyk w adminie
$(document).ready(function () {
	$('select').change(function () {
		$('.cms-form-admin-stat-object').submit();
		return false;
	});
});

