<?php

require('./mImage_class.php');

$myImage = new mImage("/home/mypath/someimage.gif");   // location of image
$myImage->create_thumbnail();                          // create new file "/home/mypath/th_someimage.gif"
$myImage->resize();                                    // resizes image if needed replace "/home/mypath/someimage.gif"

?>