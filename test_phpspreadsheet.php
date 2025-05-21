<?php
// Load Composer's autoloader
require 'vendor/autoload.php';

// Import PhpSpreadsheet classes
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Check if PhpSpreadsheet is available
if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
    echo "PhpSpreadsheet is available!<br>";
    
    // Try to create a new Spreadsheet object
    try {
        $spreadsheet = new Spreadsheet();
        echo "Successfully created a Spreadsheet object!<br>";
        
        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();
        echo "Successfully got the active sheet!<br>";
        
        // Set some values
        $sheet->setCellValue('A1', 'Hello World!');
        echo "Successfully set cell value!<br>";
        
        // Create a writer
        $writer = new Xlsx($spreadsheet);
        echo "Successfully created a writer!<br>";
        
        echo "All tests passed!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "PhpSpreadsheet is NOT available!<br>";
    
    // Check if the vendor directory exists
    if (is_dir('vendor')) {
        echo "Vendor directory exists.<br>";
        
        // Check if the PhpSpreadsheet directory exists
        if (is_dir('vendor/phpoffice/phpspreadsheet')) {
            echo "PhpSpreadsheet directory exists.<br>";
            
            // List files in the PhpSpreadsheet directory
            $files = scandir('vendor/phpoffice/phpspreadsheet');
            echo "Files in PhpSpreadsheet directory: " . implode(', ', $files) . "<br>";
        } else {
            echo "PhpSpreadsheet directory does NOT exist.<br>";
        }
    } else {
        echo "Vendor directory does NOT exist.<br>";
    }
    
    // Check if autoload.php exists
    if (file_exists('vendor/autoload.php')) {
        echo "Autoload file exists.<br>";
    } else {
        echo "Autoload file does NOT exist.<br>";
    }
    
    // Show PHP version
    echo "PHP Version: " . phpversion() . "<br>";
    
    // Show loaded extensions
    echo "Loaded Extensions: " . implode(', ', get_loaded_extensions()) . "<br>";
}
?>
