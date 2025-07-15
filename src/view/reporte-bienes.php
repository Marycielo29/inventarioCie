<?php
require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator("yo")->setLastModifiedBy("yo")->setTitle("yo")->setDescription("yo");
$activeWorksheet = $spreadsheet->getActiveSheet();
/*$activeWorksheet->setTitle("hoja 1");
$activeWorksheet->setCellValue('A1', 'Hola mundo !');
//$activeWorksheet->setCellValue('A2', 'DNI');
//$activeWorksheet->setCellValue('B2', '73885028');

// (una celda por fila)
for ($i = 1; $i <= 10; $i++) {
    $activeWorksheet->setCellValue('A' . $i, $i);
}

// (una celda por columna)
for ($i = 1; $i <= 30; $i++) {
    // Convertir número de columna a letra (A, B, C, ...)
    $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
    $activeWorksheet->setCellValue($columna . '1', $i);
}

// la tabla de multiplicar
$n1 = 1; // Columna A
// Generar la tabla del 1
for ($n2 = 1; $n2 < 12; $n2++) {
    $col = $inicioColumna; // Reiniciar al comienzo del bloque
    $activeWorksheet->setCellValue('A'.$n2, $n1);       
    $activeWorksheet->setCellValue('B'.$n2, 'X' ); 
    $activeWorksheet->setCellValue('C'.$n2, $n2);  
    $activeWorksheet->setCellValue('D'.$n2, '=' ); 
    $activeWorksheet->setCellValue('E'.$n2, $n1*$n2); 
}
*/

$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');