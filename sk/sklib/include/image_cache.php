<?php

if(defined("image_cache.php")) {
        return;
} else {
        define("image_cache.php",1);
}

/*
=pod

=head1 NAME

Image Cache - Class to "cache" dynamically created images.

=head1 Dependencies

PHP with gd and png support compiled in.

=head1 SYNOPSIS

Example:
$image = new Image_Cache("this_image.png");
if($image->get()) {
     $image->display();
}else{
     //create a png named $png
     $image->store($png);
     $image->display();
}

=head1 DESCRIPTION

Basically, Image Cache writes dynamically created images to disk on the first access so that on subsequent requests the image may be read from disk, rather than redrawn from computations.  It also has the ability to set a timeout on images so that they are recalculated after a certain duration.

=cut
*/

class Image_Cache {

/*
=pod

=head2 Varibles

=over 4

=item I<path>

The relative path to store cached images.  This directory must be readable and writeable by the webserver.

Default: ./dyn_images

=item I<image>

The full path to the cached image.  Considered private.

Default: None.  Should not be set other than by class!

=item I<Timeout>

Time, in seconds, that an image may reside on disc before being recalculated.

Default: 86400 (24 hours)

=back

=cut
*/
    var $path = "./dyn_images/";
    var $image;
    var $Timeout = 86400;

/*
=pod

=head2 Methods

=over 4

=item I<Image_Cache>

Public constructor.

Arguments:

=over 4

title - Title of image to be cached.  Should be unique.

Timeout - Time, in seconds, that an image may reside on disc before being recalculated. Default: 86400 (24 hours)

=back

Returns: nothing

Example:

=over 4

$image = new Image_Cache("thispage.png",360);

creates an Image Cache object with a timeout of 1 hour and a path of "./dyn_images/ thispage.png".

$image = new Image_Cache("thispage.png");

creates an Image Cache object with a timeout of 24 hours and a path of "./dyn_images/ thispage.png"

=back

=cut
*/

    function Image_Cache($title = "",$Timeout =  86400) {
        if($title) {
            $title = urlencode($title);
            $this->image = $this->path . $title;
        }

        $this->Timeout = $Timeout;

    }


/*
=pod

=item I<get>


Determines if an image exists, and, if so, if it was created within the timeout.

Arguments: none

Returns: 1 if the image is valid, 0 if not.

=cut
*/

    function get() {

        if(is_readable($this->image)) {
                        $stat = stat($this->image);
            if( (time() - $stat[9]) < $this->Timeout ) {
                return 1;
            }
        }

        return 0;
    }

/*
=pod

=item I<store>

Stores a png to disk.

Arguments: The resource id of the image to be stored.

Returns: nonzero value if succesfull, 0 otherwise.

=cut
*/
    function store($png) {
        return imagepng($png,$this->image);
    }

/*
=pod

=item I<display>

Handles the image output.

Arguments: none

Returns: nonzero value if succesfull, 0 otherwise.

=cut
*/
    function display() {
        $im = @imagecreatefrompng ($this->image);
        return imagepng($im);
    }
}

/*
=pod

=back

=head1 AUTHOR

M. Brian Akins, bakins@stsi.net

=cut
*/
?>
