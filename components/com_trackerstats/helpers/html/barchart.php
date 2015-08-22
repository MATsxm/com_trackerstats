<?php
/**
 * @package     Joomla.BugSquad
 * @subpackage  com_trackerstats
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * HTML utility class for creating bar charts using jQuery and jqplot JavaScript libraries.
 */
abstract class JHtmlBarchart
{
	/**
	 * Array containing information for loaded files
	 *
	 * @var    array
	 * @since  3.0
	 */
	protected static $loaded = [];

	/**
	 * Method to load the Barchart script to display a bar chart using jQuery and jqPlot
	 *
	 * @param   string  $containerID  DOM id of the element where the chart will be rendered
	 * @param   string  $urlId        DOM id of the element whose href attribute has the URL to the JSON data
	 *
	 * @return  void
	 */
	public static function barchart($containerId, $urlId, $horizontal = true, $stackSeries = true, $barMargin = 10)
	{
		// Only load once
		if (isset(static::$loaded[__METHOD__]))
		{
			return;
		}

		$orientation = ($horizontal == true) ? 'horizontal' : 'vertical';

		// Depends on jQuery UI
		$document = JFactory::getDocument();
		JHtml::_('bootstrap.framework');
		$document->addScript('components/com_trackerstats/media/js/jquery.jqplot.min.js', 'text/javascript', false);
		$document->addScript('components/com_trackerstats/media/js/jqplot.barRenderer.min.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/jqplot.categoryAxisRenderer.min.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/jqplot.pointLabels.min.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/barchart.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/jquery-ui-1.10.2.custom.min.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/jqplot.highlighter.min.js', 'text/javascript', true);
		$document->addStyleSheet(JUri::root(true) . '/components/com_trackerstats/media/css/jquery.jqplot.min.css');
		$document->addStyleSheet(JUri::root(true) . '/components/com_trackerstats/media/css/jquery-ui-1.10.2.custom.min.css');

		// Attach sortable to document
		$document->addScriptDeclaration("
			(function ($){
				$(document).ready(function (){
					var barchart = new $.JQPLOTBarchart('" . $containerId . "','" . $urlId . "','" . $orientation . "','" . $stackSeries . "','" . $barMargin . "');
					});
			})(jQuery);
			"
		);
		$document->addScriptDeclaration("
			(function ($){
				$(document).ready(function (){
    			$('#dataUpdate').click(function() {
					$('#" . $containerId . "').empty();
					// add the form variables to the URL
					var period = $('#period').val();
					var type = $('#type').val();
					var href = $('#" . $urlId . "').attr('data-href');
					var startdate = $('#start_date').val();
					var enddate = $('#end_date').val();
					href = href.substr(0, href.indexOf('format=json') + 11);
					href = href + '&amp;period=' + period + '&amp;activity_type=' + type;
					if (period == 5)
					{
						href = href + '&amp;startdate=' + startdate + '&amp;enddate=' + enddate;
					}
					$('#" . $urlId . "').attr('data-href', href);
					var barChart = new $.JQPLOTBarchart('" . $containerId . "','" . $urlId . "','" . $orientation . "','" . $stackSeries . "','" . $barMargin . "');
				});
				});
			})(jQuery);
			"
		);
		$document->addScriptDeclaration("
		/*
		 * jQuery UI Datepicker: Parse and Format Dates
		 * http://salman-w.blogspot.com/2013/01/jquery-ui-datepicker-examples.html
		 */
		(function($) {
			$(document).ready(function (){
			$('.datepicker').datepicker({
				dateFormat: 'yy-mm-dd',
				onSelect: function(dateText, inst) {
					var date = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, dateText, inst.settings);
					var dateText1 = $.datepicker.formatDate('D, d M yy', date, inst.settings);
					date.setDate(date.getDate() + 7);
					var dateText2 = $.datepicker.formatDate('D, d M yy', date, inst.settings);
					$('#dateoutput').html('Chosen date is <b>' + dateText1 + '</b>; chosen date + 7 days yields <b>' + dateText2 + '</b>');
				}
			});
		});
		})(jQuery);
		"
		);

		// Set static array
		static::$loaded[__METHOD__] = true;

		return;
	}

	public static function barchartTest($containerId, $urlId, $horizontal = true)
	{
		// Only load once
		if (isset(static::$loaded[__METHOD__]))
		{
			return;
		}

		$orientation = ($horizontal == true) ? 'horizontal' : 'vertical';

		// Depends on jQuery UI
		$document = JFactory::getDocument();
		$document->addScript('components/com_trackerstats/media/js/jquery-1.9.1.min.js', 'text/javascript', false);
		$document->addScript('components/com_trackerstats/media/js/jquery-noconflict.js', 'text/javascript', false);
		$document->addScript('components/com_trackerstats/media/js/jquery.jqplot.min.js', 'text/javascript', false);
		$document->addScript('components/com_trackerstats/media/js/jqplot.barRenderer.min.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/jqplot.categoryAxisRenderer.min.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/jqplot.pointLabels.min.js', 'text/javascript', true);
		$document->addScript('components/com_trackerstats/media/js/barchartTest.js', 'text/javascript', true);
		$document->addStyleSheet(JUri::root(true) . '/components/com_trackerstats/media/css/jquery.jqplot.min.css');

		// Attach sortable to document
		$document->addScriptDeclaration("
				(function ($){
				$(document).ready(function (){
				var barchart = new $.JQPLOTBarchartTest('" . $containerId . "','" . $urlId . "','" . $orientation . "','" . $stackSeries . "','" . $barMargin . "');
	});
	})(jQuery);
				"
		);
		$document->addScriptDeclaration("
				(function ($){
				$(document).ready(function (){
				$('button.dataUpdate').click(function() {
				$('#" . $containerId . "').empty();
				// add the form variables to the URL
				var period = $('#period').val();
				var type = $('#type').val();
				var href = $('#" . $urlId . "').attr('data-href');
				href = href + '&period=' + period + '&activity_type=' + type;
				$('#" . $urlId . "').attr('data-href', href);
				var barChart = new $.JQPLOTBarchartTest('" . $containerId . "','" . $urlId . "','" . $orientation . "','" . $stackSeries . "','" . $barMargin . "');
	});
	});
	})(jQuery);
				"
		);

		// Set static array
		static::$loaded[__METHOD__] = true;
		return;
	}
}
