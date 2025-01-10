<?
//id3.php
DEBUG_out(3,"debug3","<b>get ID3-Tags:</b>");
$filename = $filepath.$newfilename;
        $filehandle = fopen("$filename","r");
        $size = filesize("$filename");
        $seek = fseek($filehandle, $size-128);
        $tag = fgets($filehandle,128);
   
        if (strstr("$tag", "TAG") || strstr("$tag", "tag")) 
        {
            # Extract the tag data and close the file
            # This is the correct order... 

            $songtitle = trim(substr($tag,3, 30));
            $artist = trim(substr($tag,33, 30));
            $album = trim(substr($tag,63, 30));
            $year =  substr($tag,93, 4);
            $comment = trim(substr($tag,97,30));

            # Grab last char for the Genre
            # This can get confused for EOF with fgets
            $seek = fseek($filehandle, $size-1);
            $gen = ord(fgetc($filehandle));
            $genre = $genrename[$gen];

            $edit_object->attributes_vars['title']=$songtitle;
            $edit_object->attributes_vars[artist]=$artist;
            $edit_object->attributes_vars[album]=$album;
            $edit_object->attributes_vars[year]=$year;
            $edit_object->attributes_vars[genre]=$genre;

            DEBUG_out(3,"debug3","<b>Artist:</b> $artist <br><b>Songtitle:</b> $songtitle <br><b>Album:</b> $album <br><b>Year:</b> $year <br><b>Genre:</b> $genre <br>");
        }
        DEBUG_out(3,"debug3","<b>NO ID3-Tags found!</b>");
?>