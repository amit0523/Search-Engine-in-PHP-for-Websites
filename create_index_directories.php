
<?php

/* This program creates index directories for storing index files.
 * Required argument: Path to directory where the top level index
 * directory and its subdirectories will be created. The top level index
 * directory will be named index_directory.
 */

$num_directories_given = 0;
$index_dir = "";

function print_usage()
{
	echo ("Usage:\n\n" .
		  "  Syntax:\n\n" .
		  "    create_index_directories [OPTIONS] [dir_path]\n\n" .
		  "  Description:\n\n" .
		  "    create_index_directories creates index directories for storing index files.\n" .
		  "    \"dir_path\" is the path to directory where the top level index directory\n" .
		  "    and sub directories will be created. The top level index directory will\n" .
		  "    be named index_directory.\n\n" .
		  "  Options:\n\n" .
		  "    --help\n" .
		  "      Print this usage/help and exit.\n");
} // end of print_usage

for ($i = 1; $i < $argc; $i++) {
	//echo "Option " . $i . ": " . $argv[$i] . "\n";
	$arg = $argv[$i];
	if ($arg[0] === '-') {
		if ($arg === "--help") {
			print_usage();
			exit(0);
		} else {
			echo "create_index_directories: Unknown option: " . $arg . "\n";
			echo "Try create_index_directories --help to see the help.\n";
			exit(1);
		}
	} else {
		$index_dir = $arg;
		$num_directories_given++;
	}
} // end of for loop

if ($num_directories_given == 0) {
	echo "create_index_directories: One directory argument is required.\n";
	echo "Try create_index_directories --help to see the help.\n";
	exit(1);
} else if ($num_directories_given > 1) {
	echo "create_index_directories: \"Only one directory\" argument is required.\n";
	echo "Try create_index_directories --help to see the help.\n";
	exit(1);
}

if (is_dir($index_dir) != TRUE) {
	echo "create_index_directories: \"" . $index_dir . "\" is not a directory.\n";
	echo "Try create_index_directories --help to see the help.\n";
	exit(1);
}

$create_dir = $index_dir . "/index_directory";
if (file_exists($create_dir) != TRUE) {
	if (mkdir($create_dir) != TRUE) {
		echo "create_index_directories: Failed to create directory \"" . $create_dir . "\". Exiting...\n";
		exit(1);
	} else {
		echo "Created directory " . $create_dir . "\n";
	}
} else {
	echo $create_dir . " already exists.\n";
}

for ($i = 0; $i < 10; $i++) {
	$sub_dir = $create_dir . "/" . $i;
	if (file_exists($sub_dir) != TRUE) {
		if (mkdir($sub_dir) != TRUE) {
			echo "create_index_directories: Failed to create directory \"" . $sub_dir . "\". Exiting...\n";
			exit(1);
		} else {
			echo "Created directory " . $sub_dir . "\n";
		}	
	} else {
		echo $sub_dir . " already exists.\n";
	}
}

foreach (range('a', 'z') as $letter) {
	$sub_dir = $create_dir . "/" . $letter;
	if (file_exists($sub_dir) != TRUE) {
		if (mkdir($sub_dir) != TRUE) {
			echo "create_index_directories: Failed to create directory \"" . $sub_dir . "\". Exiting...\n";
			exit(1);
		} else {
			echo "Created directory " . $sub_dir . "\n";
		}	
	} else {
		echo $sub_dir . " already exists\n";
	}
}

?>
