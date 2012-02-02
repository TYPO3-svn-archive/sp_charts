if (typeof(charts) == 'undefined') {
	var charts = [];
}
jQuery(document).ready(function($) {
	jQuery('div.spcharts-chart').each(function() {
		var containerId = jQuery(this).attr('id');
		if (!containerId || typeof(charts) === 'undefined' || typeof(charts[containerId]) === 'undefined') {
			return;
		}
		var chart = charts[containerId];
		if (typeof(chart.data) === 'undefined' || typeof(chart.options) === 'undefined') {
			return;
		}

		jQuery.jqplot(containerId, chart.data, chart.options);
	});
});