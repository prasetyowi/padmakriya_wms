<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getTableTemplate')) {

	function getTableTemplate($dataTableClass='zero-configuration')
    {
		$template = array(
	        'table_open'            => '<table class="table table-striped table-bordered '.$dataTableClass.' dataTable">',

	        'thead_open'            => '<thead>',
	        'thead_close'           => '</thead>',

	        'heading_row_start'     => '<tr>',
	        'heading_row_end'       => '</tr>',
	        'heading_cell_start'    => '<th>',
	        'heading_cell_end'      => '</th>',

	        'tbody_open'            => '<tbody>',
	        'tbody_close'           => '</tbody>',

	        'row_start'             => '<tr>',
	        'row_end'               => '</tr>',
	        'cell_start'            => '<td>',
	        'cell_end'              => '</td>',

	        'row_alt_start'         => '<tr>',
	        'row_alt_end'           => '</tr>',
	        'cell_alt_start'        => '<td>',
	        'cell_alt_end'          => '</td>',

	        'table_close'           => '</table>'
		);

        return $template;
    }
}
