<?php
// Function to write data to a file
function writeFile($fileName, $content) {
    $file = fopen($fileName, "w");
    if ($file) {
        fwrite($file, $content);
        fclose($file);
        return true;
    }
    return false;
}

// Function to read data from a file
function readFileContent($fileName) {
    $file = fopen($fileName, "r");
    $content = "";
    if ($file) {
        while (!feof($file)) {
            $content .= fgets($file);
        }
        fclose($file);
    }
    return $content;
}

// Function to delete a file
function deleteFile($fileName) {
    if (file_exists($fileName)) {
        unlink($fileName);
        return true;
    }
    return false;
}
?>
