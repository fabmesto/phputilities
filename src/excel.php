<?php

namespace fab;

class excel
{
    // require FAB_PLUGIN_DIR_PATH . 'vendor/autoload.php';
    public static function read($xls_file_path, $sheet = 0, $index_intestazione = 1, $start = 1, $end = false)
    {
        $cols = array();
        $i = 0;
        $array = array();
        if (!is_file($xls_file_path)) {
            if (class_exists('WP_Error') and function_exists('__')) {
                return new \WP_Error('broke', __("File non trovato", $xls_file_path));
            } else {
                return "File non trovato";
            }
        }
        if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($xls_file_path);
            //$reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($xls_file_path);
            // prende i nomi di tutti i fogli
            $sheetNames = $spreadsheet->getSheetNames();
            //print_r($sheetNames);
            // prendi il foglio numero $sheet
            $objWorksheet = $spreadsheet->getSheet($sheet);
            // righe totali
            $totale = $objWorksheet->getHighestRow();

            // intestazione
            $rowHeader = $objWorksheet->getRowIterator($index_intestazione)->current();
            $cellIterator = $rowHeader->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $value = $cell->getValue();
                $name = $cell->getColumn();
                $cols[trim($value)] = trim($name);
            }
            // righe
            foreach ($objWorksheet->getRowIterator($index_intestazione + $start) as $row) {
                $rowIndex = $row->getRowIndex();
                // controlla se la riga di fine è stata impostata
                // e se la riga corrente è maggiore stop il ciclo
                if ($end && ($rowIndex - $index_intestazione) > $end) {
                    break;
                }

                foreach ($cols as $key => $colIndex) {
                    // cella esempio: 'A1'
                    $cell = $objWorksheet->getCell($colIndex . $rowIndex);
                    $value = $cell->getValue();
                    // se è una data lo formatta in 'Y-m-d'

                    //if(\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTimeFormatCode($cell)) {

                    if ($value != null && \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                        $time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                        if ($time > 0) {
                            $value = date('Y-m-d', $time);
                        } else {
                            $value = null;
                        }
                    }
                    // se è null mette vuoto
                    if ($value == null) {
                        $value = '';
                    }

                    if ($key != '') {
                        $array[$rowIndex][$key] = $value;
                    }
                }
            }
            return array('tot' => $totale, 'rows' => $array, 'cols' => $cols, 'sheets' => $sheetNames, 'sheet' => $sheet);
        } else {
            if (class_exists('WP_Error') and function_exists('__')) {
                return new \WP_Error('broke', __("Classe non trovata", "PhpOffice"));
            } else {
                return "Classe non trovata";
            }
        }
    }
}
