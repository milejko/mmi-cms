<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

/**
 * Generator kodu dla biblioteki wykresów FLOT
 */
class StatFlot {

	/**
	 * Generuje kod HTML dla biblioteki flot
	 * @param string $chartName
	 * @param array $series serie danych
	 * @param string $type
	 * @param boolean $points
	 * @param boolean $labels
	 * @param type $legendContainer
	 * @return type
	 */
	public static function getCode($chartName, array $series, $type = 'lines', $points = false, $labels = true) {
		$points = $points ? 'true' : 'false';
		//nagłówek
		$html = '$(function () {';
		$html = trim($html, ', ') . ';';
		$min = 1000000000000;
		$max = -1000000000000;
		$tickSeries = [];
		$j = 0;
		//pętla po seriach
		foreach ($series as $chart) {
			$i = 0;
			$first = true;
			$html .= $chartName . '_' . str_replace('-', '_', $chart['object']) . '_' . $j . ' = [';
			foreach ($chart['data'] as $label => $count) {
				$i++;
				if ($count < $min) {
					$min = $count;
				}
				if ($count > $max) {
					$max = $count;
				}
				$html .= '[' . $i . ', ' . $count . '], ';
				$tickSeries[$j][] = $label;
			}
			$html = trim($html, ', ') . '];';
			$first = false;
			$j++;
		}
		foreach ($tickSeries as $key => $ticks) {
			$html .= $chartName . '_ticks_' . $key . ' = [';
			foreach ($ticks as $tick) {
				$html .= '\'' . $tick . '\',';
			}
			$html = trim($html, ',') . '];';
			$html .= '$(\'#' . $chartName . '\').bind(\'plothover\', function (event, pos, item) {handleTooltip(event, pos, item, ' . $chartName . '_ticks_' . $key . ', ' . $key . ');});';
		}

		$max = $max + 15 / 100 * $max;
		if ($min > 0) {
			$min = $min - 70 / 100 * $min;
		}
		$html .= 'var ' . $chartName . ' = $.plot($(\'#' . $chartName . '\'), [';
		$i = 0;
		foreach ($series as $chart) {
			$html .= '{data: ' . $chartName . '_' . str_replace('-', '_', $chart['object']) . '_' . $i . ', label: \'' . $chart['label'] . '\'}, ';
			$i++;
		}
		$html = trim($html, ', ') . '], ';
		$html .= '{
               series: {
                   ' . $type . ': { show: true },
                   points: { show: ' . $points . ' }
               },';
		$html .= 'legend: { margin: [0, 0], backgroundOpacity: 0 },';
		$html .= 'grid: { hoverable: true, clickable: true },
               yaxis: { min: ' . $min . ', max: ' . $max . ' },
			   xaxis: {';
		$html .= 'ticks: [';
		$i = 0;
		if (isset($ticks)) {
			$div = round(count($ticks) * strlen($ticks[0]) / 60);
			if ($div < 1) {
				$div = 1;
			}
			if ($labels) {
				foreach ($tickSeries[0] as $tick) {
					$i++;
					if (($i - 1) % $div == 0) {
						$html .= '[' . $i . ', \'' . $tick . '\'], ';
					}
				}
			}
		}
		$html = trim($html, ', ') . ']';
		$html .= '}
			});';
		return $html . '});';
	}

}
