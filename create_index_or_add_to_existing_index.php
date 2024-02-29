
<?php

/* This program takes files/directories as arguments and parses the
 * files (present in directories or given on command line) to create the
 * search index files. The directories are processed recursively if -r option
 * is given. This program also requires the path to directory where
 * a directory called index_directory exists. This index_directory
 * contains 36 folders named 0, 1, 2, .., 9 and a, b, c, .., y, z.
 * Index files are created in subdirectories of index_directory.
 * This program works on text/html files only. You can use program
 * create_index_directories.php to create index_directory and its subdirectories.
 */

// error handler function
function custom_error_handler($errno, $errstr, $errfile, $errline)
{
    //echo "Got error/notice/warning, etc. Exiting..\n";
    echo "Got error/notice/warning, etc.\n";
    echo $errno. "\n";
    echo $errtsr . "\n";
    echo $errfile . "\n";
    echo $errline . "\n";
    //echo "Exit status is 1.\n";
    //exit(1);
} // end of custom_error_handler
// set to the user defined error handler
$old_error_handler = set_error_handler("custom_error_handler");

function print_usage()
{
    echo ("Usage:\n\n" .
          "  Syntax:\n\n" .
          "    create_index_or_add_to_existing_index OPTION[S] [FILE...] [DIR...]\n\n" .
          "  Description:\n\n" .
          "    create_index_or_add_to_existing_index parses a file and creates search index files\n" .
          "    or adds to already existing index files. It works on text/html files only.\n" .
          "    The file can be given as an argument or it may be present in a directory\n" .
          "    which itself has been given as an argument. This program also requires\n" .
          "    the path to directory where a directory called index_directory\n" .
          "    and its subdirectories (0-9, a-z) exist. You can use\n" .
          "    program create_index_directories.php to create index_directory\n" .
          "    and its subdirectories. The paths to file/dir to be indexed should be\n" .
          "    relative to server_root_directory_path (to be given by specifying -s option).\n\n" .
          "  Options:\n\n" .
          "     -i path_to_index_directory (MANDATORY option)\n" .
          "        Use -i option to specify the path to directory where directory\n" .
          "        called index_directory and its subdirectories (0-9, a-z) exist.\n" .
          "        Index files are created in subdirectories of index_directory.\n\n" .
          "     -r\n" .
          "        Specify -r option to process directory/directories recursively.\n\n" .
          "     -p prefix_path\n" .
          "        Please give a prefix to add before the file path that will be written to\n" .
          "        index files. It could be something like https://mywebsite.com. If the\n" .
          "        file path abcd/tyr.html is going to be written to index file then it\n" .
          "        will actually write https://mywebsite.com/abcd/tyr.html in the index file\n" .
          "        if -p option is present.\n\n" .
          "     -s server_root_directory_path (MANDATORY option)\n" .
          "        The \"absolute\" path to server root directory (from where index.html or index.php will be served).\n" .
          "        The paths to file/dir to be indexed should be relative to server_root_directory_path.\n\n" .
          "    --help\n".
          "        Print this usage/help and exit.\n\n" .
          " So, basically the file to be indexed is found by combining server_root_directory_path\n" .
          " and path to files/directories given on command line while the file contents\n" .
          " to be written is formed by combining prefix and path to files/directories given\n" .
          " on command line.\n");
} // end of print_usage

$iOptionPresent = FALSE;
$rOptionPresent = FALSE;
$pOptionPresent = FALSE;
$sOptionPresent = FALSE;
$index_dir_parent = "";
$index_dir = "";
$prefix = "";
$server_root_path = "";
$file_dir_array = array();
$num_files_processed = 0;

for ($i = 1; $i < $argc; $i++) {
    echo "debug: Argument/Option " . $i . ": " . $argv[$i] . "\n";
    $arg = $argv[$i];
    if ($arg[0] === '-') {
        if ($arg === "--help") {
            print_usage();
            exit(0);
        } else if ($arg === "-r") {
            $rOptionPresent = TRUE;
        } else if ($arg === "-i") {
            $iOptionPresent = TRUE;
            if (($i+1) < $argc) {
                $index_dir_parent = $argv[$i+1];
                $index_dir = $index_dir_parent . "/" . "index_directory";
                $i++;
                continue;
            }
        } else if ($arg === "-p") {
            $pOptionPresent = TRUE;
            if (($i+1) < $argc) {
                $prefix = $argv[$i+1];
                if ((substr($prefix, -1, 1) != "/") && (substr($prefix, -1, 1) != "\\")) {
                    $prefix = $prefix . "/";
                }
                $i++;
                continue;
            }
        } else if ($arg === "-s") {
            $sOptionPresent = TRUE;
            if (($i+1) < $argc) {
                $server_root_path = $argv[$i+1];
                if ((substr($server_root_path, -1, 1) != "/") && (substr($server_root_path, -1, 1) != "\\")) {
                    $server_root_path = $server_root_path . "/";
                }
                $i++;
                continue;
            }
        } else {
            echo "create_index_or_add_to_existing_index: Unknown option: " . $arg . "\n";
            echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
            exit(1);
        }
    } else {
        array_push($file_dir_array, $arg);
    }
} // end of for loop

// debug info
echo "\nDEBUG_INFO_START:\n\n";
if ($rOptionPresent === TRUE) {
    echo "-r option is present.\n";
} else {
    echo "-r option is NOT present.\n";
}
if ($iOptionPresent === TRUE) {
    echo "-i option is present.\n";
    echo "index_dir_parent = " . $index_dir_parent . "\n";
} else {
    echo "-i option is NOT present.\n";
}
if ($pOptionPresent === TRUE) {
    echo "-p option is present.\n";
    echo "prefix = " . $prefix . "\n";
} else {
    echo "-p option is NOT present.\n";
}
if ($sOptionPresent === TRUE) {
    echo "-s option is present.\n";
    echo "server_root_path = " . $server_root_path . "\n";
} else {
    echo "-s option is NOT present.\n";
}
$num_entries = count($file_dir_array);
echo "Entries in file_dir_array are:\n";
for ($i = 0; $i < $num_entries; $i++){
    echo $file_dir_array[$i] . "\n";
}
echo "\nDEBUG_INFO_END\n\n";
// end debug info

if ($index_dir_parent == "") {
    echo "create_index_or_add_to_existing_index: Please give the path to directory where index_directory exist.\n";
    echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if ($server_root_path == "") {
    echo "create_index_or_add_to_existing_index: Please give the path to server root directory.\n";
    echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (file_exists($index_dir_parent) != TRUE) {
    echo "create_index_or_add_to_existing_index: \"" . $index_dir_parent . "\" does not exist.\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (is_dir($index_dir_parent) != TRUE) {
    echo "create_index_or_add_to_existing_index: \"" . $index_dir_parent . "\" is not a directory.\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (file_exists($index_dir) != TRUE) {
    echo "create_index_or_add_to_existing_index: \"index_directory\" does not exist in \"" . $index_dir_parent . "\".\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (is_dir($index_dir) != TRUE) {
    echo "create_index_or_add_to_existing_index: index_directory \"" . $index_dir . "\" is not a directory.\n";
    echo "Please give a valid path to directory where index_directory exist.\n";
    echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
    echo "Exiting..\n";
    exit(1);
}

if (count($file_dir_array) < 1) {
    echo "create_index_or_add_to_existing_index: No files/directories given for indexing.\n";
    echo "Try create_index_or_add_to_existing_index --help to see the help.\n";
    exit(0);
}

// Check if all index directories exist
echo "create_index_or_add_to_existing_index: checking whether all index directories exist..\n";
for ($i = 0; $i < 10; $i++) {

    $sub_dir = $index_dir . "/" . $i;
    if (file_exists($sub_dir) != TRUE) {
        echo $sub_dir . " does not exist.\n";
        echo "Exiting..\n";
        exit(1);
    }
    if (is_dir($sub_dir) != TRUE) {
        echo $sub_dir . " is not a directory.\n";
        echo "Exiting..\n";
        exit(1);
    }

} // end of for loop

foreach (range('a', 'z') as $letter) {

    $sub_dir = $index_dir . "/" . $letter;
    if (file_exists($sub_dir) != TRUE) {
        echo $sub_dir . " does not exist.\n";
        echo "Exiting..\n";
        exit(1);
    }
    if (is_dir($sub_dir) != TRUE) {
        echo $sub_dir . " is not a directory.\n";
        echo "Exiting..\n";
        exit(1);
    }

} // end of foreach loop

echo "All index directories exist.\n\n";

echo "\n\n**** Starting Indexing.. ****\n\n";

$num_entries = count($file_dir_array);
for ($i = 0; $i < $num_entries; $i++) {

    $file_rl_path = $file_dir_array[$i];
    $file = $server_root_path . $file_rl_path;

    if (file_exists($file) != TRUE) {
        echo "\"" . $file . "\" does not exist.\n";
    } else if (is_file($file) == TRUE) {
        process_file($file, $file_rl_path);
    } else if (is_dir($file) == TRUE) {
        process_dir($file);
    } else {
        echo "\"" . $file . "\": No such file or directory.\n";
    }

} // end of for loop

function process_dir($dir) {

    //echo $dir . "\n";
    $files = scandir($dir);
    if ($files == FALSE) {
        return;
    }
    $num = count($files);
    for ($i = 0; $i < $num; $i++) {
        if (($files[$i] === ".") || ($files[$i] === "..")) {
            continue;
        }
        $file_entry = $dir . "/" . $files[$i];
        if (file_exists($file_entry) != TRUE) {
            echo "\"" . $file_entry . "\" does not exist.\n";
        } else if (is_file($file_entry) == TRUE) {
            $empty_string = "";
            $root_path = $GLOBALS['server_root_path'];
            $file_rl_path = str_replace($root_path, $empty_string, $file_entry);
            //echo "Old file_rl_path  = " . $file_entry . ", New file_rl_path  = " . $file_rl_path . "\n";
            process_file($file_entry, $file_rl_path);
        } else if (is_dir($file_entry) == TRUE) {
            if ($GLOBALS['rOptionPresent'] === TRUE) {
                process_dir($file_entry);
            } else {
                //echo $file_entry . "\n"; // remove this later // TODO
            }
        } else {
            echo "\"" . $file_entry . "\": No such file or directory.\n";
        }
    } // end of for loop

} // end of process_dir

function process_file($file, $file_rl_path) {

    //echo $file . "\n";
    $handle = fopen($file, "r");
    if ($handle == FALSE) {
        echo "Error: Failed to open file \"" . $file . "\"\n";
        return;
    }

    echo "\n\nIndexing file \"" . $file . "\"\n";

    // read file
    $line_num = 0;
    while (($line = fgets($handle)) != FALSE) {
        /*
        //echo $line;
        $line_num++;
        $len = strlen($line);
        echo "line number " . $line_num . " length = " . $len . "\n";
        */
        $pattern = "([0-9A-Za-z][0-9A-Za-z][0-9A-Za-z][0-9A-Za-z]*)";
        preg_match_all($pattern, $line, $matches, PREG_SET_ORDER);
        $match_count = count($matches);
        for ($j = 0; $j < $match_count; $j++) {
            $word = $matches[$j][0];
            //echo $word . "\n";
            $word_l = strtolower($word);
            //echo $word_l . "\n";
            process_word_l($word_l, $file, $file_rl_path);
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail when reading file \"" . $file . "\"\n";
    }
    fclose($handle);

    echo "Indexing file \"" . $file . "\" completed.\n";
    $GLOBALS['num_files_processed'] = $GLOBALS['num_files_processed'] + 1;
    echo "Total files indexed = " . $GLOBALS['num_files_processed'] . "\n";

} // end of process_file

function process_word_l($word_l, $file, $file_rl_path) {

    $letter = substr($word_l, 0 , 1);
    $dir_to_check = $GLOBALS['index_dir'] . "/" . $letter;
    $file_to_check =  $dir_to_check . "/" . $word_l;
    $content_without_newline = $GLOBALS['prefix'] . $file_rl_path;
    $content = $content_without_newline . "\n";

    //create file if file does not exist
    if (file_exists($file_to_check) != TRUE) {
        //echo "\"" . $file_to_check . "\" does not exist. Creating it..\n";
        if (file_put_contents($file_to_check, $content) == FALSE) {
            echo "Error: file_put_contents failed for file \"" . $file_to_check . "\"\n";
        }
        return;
    }

    //echo "debug: file_to_check = " . $file_to_check . "\n";
    //echo "debug: file_to_check = " . $file_to_check . "\n";
    //echo "debug: file_to_check = " . $file_to_check . "\n";
    //echo "debug: file_to_check = " . $file_to_check . "\n";

    $handle = fopen($file_to_check, "r+");
    if ($handle == FALSE) {
        echo "Error: Failed to open file \"" . $file_to_check . "\"\n";
        return;
    }

    // check if entry exists and if not then append at the end
    while (($line = fgets($handle)) != FALSE) {
        if ($line === $content) {
            //echo "Entry \"" . $content_without_newline . "\" already exists in file \"" . $file_to_check ."\"\n";
            return;
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail when reading file \"" . $file_to_check . "\"\n";
    }
    fwrite($handle, $content);
    fclose($handle);

} // end of process_word_l

echo "\n\n**** Indexing complete.**** \n\n";

?>
