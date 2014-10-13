<?php
       $file_dir = isset($_REQUEST['filedir'])?"../../".$_REQUEST['filedir']:"../../data/upfiles/ablums/";
	    if (isset($_FILES["Filedata"])) { // test if file was posted
                $orginal_file_name= strtolower(basename($_FILES["Filedata"]["name"])); //get lowercase filename
                $file_ending= substr($orginal_file_name, strlen($orginal_file_name)-4, 4); //file extension

                if (in_array(strtolower($file_ending), array(".jpg", ".gif", ".png"), true)) { // file filter...
                // ...don't forget that file extension can be fake!

                        $file= $file_dir.sha1($orginal_file_name."|".rand(0,99999)).$file_ending;
                        // path 'uploaded_data/' must exist! It's recommended that you store files with unique
                        // names and not with original names.

                        if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $file)) { // move posted file...
                                /*
                                TO-DO:
                                insert your PHP code to execute when file has been posted
                                */
                        }
                }
        }
        else {
			echo 1;
                /*
                TO-DO:
                insert your PHP code to execute when no file has been posted
                */
        }
?> 