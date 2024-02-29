
Architecture of this Search Engine
----------------------------------

This search engine has a new architecture compared to other search engines.

I invented and implemented this new search engine architecture.

This search engine has been developed mainly for English Alphabet. This search
engine is based on the fact that no letter in English Alphabet has more than
80,000 words starting with it. This search engine works on text/html files only.

This search engine was mainly developed so that it could be used on websites. So,
now websites can integrate this search engine on their platform so that a user can
search anything on their website. The website can index all their pages through this
search engine and also give a search box to the user. The websites now do not have to
rely on third party search engines.

The structure of the search index is that there is a top level directory called
index_directory. This directory has 36 folders. The folders are named: 0, 1, 2, .., 8, 9 and
a, b, c, .., y, z. Every word has an index file name with the same name in the directory
which starts with the same letter as the word. So, since no letter has more than
80,000 words starting with it, there will be at max only 80,000 files in that directory.
These days modern OSes can handle many more files in one directory.

For example, if the word is "server", then there will be a file in "index_directory/s" folder
called "server". This file will contain the path of all documents that contain the word
"server".

So, the contents of the file server can be:
https://www.myexample.com/abcd.html
https://www.myexample.com/1234.html
https://www.myexample.com/hello.html

These three html documents contain the word "server". Now, if someone wants to
search for the word "server" then the contents of this file will be printed on
the output page/screen which means that these 3 documents contain the word "server".

Now, let's suppose there is another word called "hello". So, there will be a file
in "index_directory/h" called hello and this will contain the path of all documents
that contain the word "hello".

Let's suppose that the index file "hello" has following contents:
https://www.myexample.com/xyz.html
https://www.myexample.com/new.html
https://www.myexample.com/hello.html

Now, if someone search for both keywords "server" and "hello", the output will be:
https://www.myexample.com/hello.html
https://www.myexample.com/abcd.html
https://www.myexample.com/1234.html
https://www.myexample.com/xyz.html
https://www.myexample.com/new.html

So, you see that "https://www.myexample.com/hello.html" is the first URL to be
printed because it contains both "server" and "hello" words. So, the document
which contains most number of search words will be printed first and then documents
which contain less number of search words. So, basically the printing is sorted
in descending order according to the number of search words present in the document.

Programs in this Search Engine
------------------------------
There are three programs developed in PHP in this Search Engine. So, it will run
on all platforms that have PHP installed. The three programs are:

* create_index_directories.php
* create_index_or_add_to_existing_index.php
* search_index.php

* create_index_directories.php: This program creates index directories for storing
  index files. Required argument: Path to directory where the top level index
  directory and its subdirectories will be created. The top level index
  directory will be named index_directory.

  Usage:

    Syntax:
        create_index_directories [OPTIONS] [dir_path]

            Description:
                create_index_directories creates index directories for storing
                index files. "dir_path" is the path to directory where the top
                level index directory and sub directories will be created.
                The top level index directory will be named index_directory.

            Options:
                --help
                    Print this usage/help and exit

* create_index_or_add_to_existing_index.php: This program takes files/directories as arguments
  and parses the files (present in directories or given on command line) to create the
  search index files or add to already existing index files. The directories are processed
  recursively if -r option is given. This program also requires the path to directory where
  a directory called index_directory exists. This index_directory
  contains 36 folders named 0, 1, 2, .., 9 and a, b, c, .., y, z.
  Index files are created in subdirectories of index_directory. This program
  works on text/html files only. You can use program create_index_directories.php
  to create index_directory and its subdirectories.

    Usage:

        Syntax:
            create_index_or_add_to_existing_index OPTION[S] [FILE...] [DIR...]

            Description:
                create_index_or_add_to_existing_index parses a file and creates search index files
                or adds to already existing index files. It works on text/html files only.
                The file can be given as an argument or it may be present in a directory which itself has been
                given as an argument. This program also requires the path to directory
                where a directory called index_directory and its subdirectories (0-9, a-z) exist.
                You can use program create_index_directories.php to create
                index_directory and its subdirectories. The paths to file/dir to
                be indexed should be relative to server_root_directory_path
                (to be given by specifying -s option).

            Options:
               -i path_to_index_directory (MANDATORY option)
                  Use -i option to specify the path to directory where directory
                  called index_directory and its subdirectories (0-9, a-z) exist.
                  Index files are created in subdirectories of index_directory.

               -r
                  Specify -r option to process directory/directories recursively.

               -p prefix_path
                  Please give a prefix to add before the file path that will be written to
                  index files. It could be something like https://mywebsite.com. If the
                  file path abcd/tyr.html is going to be written to index file then it
                  will actually write https://mywebsite.com/abcd/tyr.html in the index file
                  if -p option is present.

               -s server_root_directory_path (MANDATORY option)
                  The "absolute" path to server root directory (from where index.html or index.php will be served).
                  The paths to file/dir to be indexed should be relative to server_root_directory_path.

              --help
                  Print this usage/help and exit.

    So, basically the file to be indexed is found by combining server_root_directory_path
    and path to files/directories given on command line while the file contents
    to be written is formed by combining prefix and path to files/directories given
    on command line.

* search_index.php: This program searches for search words in index files. This program
  requires the path to directory where a directory called index_directory exists.
  This index_directory contains 36 subdirectories named 0, 1, 2, .., 9 and a, b, c, .., y, z.
  The index files are present in these subdirectories.

    Usage:

        Syntax:
            search_index OPTION[S] [search_word[s]...]

        Description:
            search_index searches for search_word[s] in index files. One or more
            search words can be specified. This program requires the path to directory
            where a directory called index_directory and its subdirectories (0-9, a-z)
            exist. The index files are present in these subdirectories.

            Options:
              -i path_to_index_directory (MANDATORY option)
                  Use -i option to specify the path to directory where directory
                  called index_directory exist.

              --help
                  Print this usage/help and exit.

_________End of ReadMe.txt_________
