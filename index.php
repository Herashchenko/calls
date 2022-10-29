<?php

use \TestTask\BuildReport;

require_once __DIR__.'/classLoader.php';

$data = file_get_contents("data.json");
$build = new BuildReport();
$callDTO = $build->fillCallDtoSpl($data);

echo $build->buildServerOverLoadTable($callDTO);

echo  '</br>';
echo  '</br>';

echo $build->buildServerLoadTable($callDTO);
