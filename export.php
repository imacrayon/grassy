<?php

$data = json_decode($_POST['export_data'], true);
$name = $_POST['export_name'];
$grid = $_POST['export_grid'];

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$name}_data.csv");
header("Pragma: no-cache");
header("Expires: 0");


$headings = array_map(function($title){
	return ucwords(str_replace('_', ' ', $title));
}, array_keys($data[0]));

$grid_label = ['Grid Size', $grid];
array_pad($grid_label, (count($data[0]) - 2));


array_unshift($data, $headings);
array_unshift($data, $grid_label);
	
$file = fopen('php://output', 'w');
foreach ($data as $row) {
	fputcsv($file, $row);
}
fclose($file);