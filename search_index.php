<?php

/* This program searches for search words in index files. This program
 * requires the path to directory where a directory called index_directory exists.
 * This index_directory contains 36 subdirectories named 0, 1, 2, .., 9 and a, b, c, .., y, z.
 * The index files are present in these subdirectories.
 */

function print_usage()
{
    echo ("Usage:\n\n" .
          "  Syntax:\n\n" .
          "    search_index OPTION[S] [search_word[s]...]\n\n" .
          "  Description:\n\n" .
          "    search_index searches for search_word[s] in index files. One or more\n" .
          "    search words can be specified. This program requires the path to directory\n" .
          "    where a directory called index_directory and its subdirectories (0-9, a-z)\n" .
          "    exist. The index files are present in these subdirectories.\n\n" .
          "  Options:\n\n" .
          "    -i path_to_index_directory (MANDATORY option)\n" .
          "        Use -i option to specify the path to directory where directory\n" .
          "        called index_directory exist.\n\n" .
          "    --help\n".
          "        Print this usage/help and exit.\n");
} // end of print_usage

$iOptionPresent = FALSE;
$index_dir_parent = "";
$index_dir = "";
$search_keyword_array = array();
$search_results_array = array();

for ($i = 1; $i < $argc; $i++) {
    echo "debug: Argument/Option " . $i . ": " . $argv[$i] . "\n";
    $arg = $argv[$i];
    if ($arg[0] === '-') {
        if ($arg === "--help") {
            print_usage();
            exit(0);
        } else if ($arg === "-i") {
            $iOptionPresent = TRUE;
            if (($i+1) < $argc) {
                $index_dir_parent = $argv[$i+1];
                $index_dir = $index_dir_parent . "/" . "index_directory";
                $i++;
                continue;
            }
        } else {
            echo "search_index: Unknown option: " . $arg . "\n";
            echo "Try search_index --help to see the help.\n";
            exit(1);
        }
    } else {
        array_push($search_keyword_array, $arg);
    }
} // end of for loop

// debug info
echo "\nDEBUG_INFO_START:\n\n";
if ($iOptionPresent === TRUE) {
    echo "-i option is present.\n";
    echo "index_dir_parent = " . $index_dir_parent . "\n";
} else {
    echo "-i option is NOT present.\n";
}

$num_entries = count($search_keyword_array);
echo "Entries in search_keyword_array are:\n";
for ($i = 0; $i < $num_entries; $i++){
    echo $search_keyword_array[$i] . "\n";
}
echo "\nDEBUG_INFO_END\n\n";
// end debug info

if ($index_dir_parent == "") {
    echo "search_index: Please give the path to directory where index_directory exist.\n";
    echo "Try search_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (file_exists($index_dir_parent) != TRUE) {
    echo "search_index: \"" . $index_dir_parent . "\" does not exist.\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try search_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (is_dir($index_dir_parent) != TRUE) {
    echo "search_index: \"" . $index_dir_parent . "\" is not a directory.\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try search_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (file_exists($index_dir) != TRUE) {
    echo "search_index: \"index_directory\" does not exist in \"" . $index_dir_parent . "\".\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try search_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (is_dir($index_dir) != TRUE) {
    echo "search_index: index_directory \"" . $index_dir . "\" is not a directory.\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try search_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (count($search_keyword_array) < 1) {
    echo "search_index: No search word given for searching.\n";
    echo "Try search_index --help to see the help.\n";
    exit(0);
}

$num_entries = count($search_keyword_array);
for ($i = 0; $i < $num_entries; $i++) {
    $word = $search_keyword_array[$i];
    $word_l = strtolower($word);
    $letter = substr($word_l, 0 , 1);
    $dir_to_check = $GLOBALS['index_dir'] . "/" . $letter;
    $file_to_check =  $dir_to_check . "/" . $word_l;

    if (file_exists($file_to_check) != TRUE) {
        continue;
    }
    if (is_file($file_to_check) != TRUE) {
        continue;
    }
    $handle = fopen($file_to_check, "r");
    if ($handle == FALSE) {
        //echo "Error: Failed to open file \"" . $file_to_check . "\"\n";
        continue;
    }

    while (($line = fgets($handle)) != FALSE) {
        // remove newline from line
        $line = str_replace(array("\n", "\r"), '', $line);
        //$old_value = $search_results_array[$line];
        //if (($old_value == NULL) || ($old_value == FALSE)) {
        //    $old_value = 0;
        //}
        if (array_key_exists($line, $search_results_array) == FALSE) {
          $search_results_array[$line] = 1;
        } else { 
          $search_results_array[$line]++;
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail when reading file \"" . $file_to_check . "\"\n";
    }
    fclose($handle);
} // end of for loop

// dump search_results_array after sorting
arsort($search_results_array);
//var_dump($search_results_array);
$keys = array_keys($search_results_array);
$num_entries = count($keys);
for ($i = 0; $i < $num_entries; $i++) {
    echo $keys[$i]. "\n";
} // end of for loop

?>
