<?php
// PHP Layers Menu 2.2.0 (C) 2001-2002 Marco Pratesi (marco at telug dot it)

/**
* This is the base class of the PHP Layers Menu system.
*
* This class depends on the Template class of PHPLib
*
* @version 2.2.0
* @package PHPLayersMenu
*/
class LayersMenu{

/**
* The name of the package
* @access private
* @var string
*/
var $package_name;
/**
* The version of the package
* @access private
* @var string
*/
var $version;
/**
* The copyright of the package
* @access private
* @var string
*/
var $copyright;
/**
* The author of the package
* @access private
* @var string
*/
var $author;

/**
* The base directory where the package is installed
* @access private
* @var string
*/
var $dirroot;
/**
* The "lib" directory of the package
* @access private
* @var string
*/
var $libdir;
/**
* The http path corresponding to libdir
* @access private
* @var string
*/
var $libwww;
/**
* The directory where templates can be found
* @access private
* @var string
*/
var $tpldir;
/**
* The directory where images related to the menu can be found
* @access private
* @var string
*/
var $imgdir;
/**
* The http path corresponding to imgdir
* @access private
* @var string
*/
var $imgwww;
/**
* Do you want that code execution halts on error?
* @access private
* @var string
*/
var $halt_on_error = "no";
/**
* The template to be used for the first level menu of a horizontal menu.
*
* The value of this variable is significant only when preparing
* a horizontal menu.
*
* @access private
* @var string
*/
var $horizontal_menu_tpl;
/**
* The template to be used for the first level menu of a vertical menu.
*
* The value of this variable is significant only when preparing
* a vertical menu.
*
* @access private
* @var string
*/
var $vertical_menu_tpl;
/**
* The template to be used for submenu layers
* @access private
* @var string
*/
var $sub_menu_tpl;
/**
* The string containing the menu structure
* @access private
* @var string
*/
var $menu_structure;
/**
* The character used in the menu structure format to separate fields of each item
* @access private
* @var string
*/
var $separator;

/**
* It counts nodes for all menus
* @access private
* @var integer
*/
var $nodes_count;
/**
* A multi-dimensional array where we store informations for each menu entry
* @access private
* @var array
*/
var $tree;
/**
* The maximum hierarchical level of menu items
* @access private
* @var integer
*/
var $max_level;
/**
* An array that counts the number of first level items for each menu
* @access private
* @var array
*/
var $first_level_cnt;
/**
* An array containing the number identifying the first item of each menu
* @access private
* @var array
*/
var $first_item;
/**
* An array containing the number identifying the last item of each menu
* @access private
* @var array
*/
var $last_item;
/**
* A string containing the header needed to use the menu(s) in the page
* @access private
* @var string
*/
var $header;
/**
* Number of layers
* @access private
* @var integer
*/
var $numl;
/**
* The JS function to list layers
* @access private
* @var string
*/
var $listl;
/**
* The JS vector to know the father of each layer
* @access private
* @var string
*/
var $father;
/**
* The JS function to set initial positions of all layers
* @access private
* @var string
*/
var $moveLayers;
/**
* An array containing the code related to the first level menu of each menu
* @access private
* @var array
*/
var $first_level_menu;
/**
* A string containing the footer needed to use the menu(s) in the page
* @access private
* @var string
*/
var $footer;

/**
* The HTML string that is used for forward arrows.
*
* This string can contain either the HTML code of a "text-only" forward arrow,
* e.g. " --&gt;" or the complete HTML tag corresponding to an image used
* as forward arrow
*
* @access private
* @var string
*/
var $forward_arrow;
/**
* Completely analogous to forward_arrow
* @access private
* @var string
*/
var $down_arrow;
/**
* Step for the left boundaries of layers
* @access private
* @var integer
*/
var $abscissa_step;
/**
* Estimated value of the vertical distance between adjacent links on a generic layer
* @access private
* @var integer
*/
var $ordinate_step;
/**
* Threshold for vertical repositioning of a layer
* @access private
* @var integer
*/
var $thresholdY;

/**
* The constructor method; it initializates the menu system
* @return void
*/
function LayersMenu_(
    $abscissa_step = 140,
    $ordinate_step = 20,
    $thresholdY = 20
    ) {

    $this->package_name = "PHP Layers Menu";
    $this->version = "2.2.0";
    $this->copyright = "(C) 2001-2002";
    $this->author = "Marco Pratesi (marco at telug dot it)";

    $this->dirroot = "";
    $this->libdir = "lib/";
    $this->libwww = "lib/";
    $this->tpldir = "templates/";
    $this->imgdir = "images/";
    $this->imgwww = "images/";
    $this->horizontal_menu_tpl = $this->dirroot . $this->tpldir . "layersmenu-horizontal_menu.ihtml";
    $this->vertical_menu_tpl = $this->dirroot . $this->tpldir . "layersmenu-vertical_menu.ihtml";
    $this->sub_menu_tpl = $this->dirroot . $this->tpldir . "layersmenu-sub_menu.ihtml";
    $this->menu_structure = "";
    $this->separator = "|";

    $this->nodes_count = 0;
    $this->tree = array();
    $this->max_level = array();
    $this->first_level_cnt = array();
    $this->first_item = array();
    $this->last_item = array();
    $this->header = "";
    $this->numl = 0;
    $this->listl = "";
    $this->father = "";
    $this->moveLayers = "";
    $this->first_level_menu = array();
    $this->footer = "";

    $this->forward_arrow = " --&gt;";
    $this->down_arrow = " --&gt;";
    $this->abscissa_step = $abscissa_step;
    $this->ordinate_step = $ordinate_step;
    $this->thresholdY = $thresholdY;
}

/**
* The method to set the value of abscissa_step
* @access public
* @return void
*/
function set_abscissa_step($abscissa_step) {
    $this->abscissa_step = $abscissa_step;
}

/**
* The method to set the value of ordinate_step
* @access public
* @return void
*/
function set_ordinate_step($ordinate_step) {
    $this->ordinate_step = $ordinate_step;
}

/**
* The method to set the value of thresholdY
* @access public
* @return void
*/
function set_thresholdY($thresholdY) {
    $this->thresholdY = $thresholdY;
}

/**
* The method to set the dirroot directory
* @access public
* @return boolean
*/
function set_dirroot($dirroot) {
    if (!is_dir($dirroot)) {
        $this->error("set_dirroot: $dirroot is not a directory.");
        return false;
    }
    if (substr($dirroot, -1) != "/") {
        $dirroot .= "/";
    }
    $this->dirroot = $dirroot;
    return true;
}

/**
* The method to set the libdir directory
* @access public
* @return boolean
*/
function set_libdir($libdir) {
    if (substr($libdir, -1) == "/") {
        $libdir = substr($libdir, 0, strlen($libdir)-1);
    }
    if (str_replace("/", "", $libdir) == $libdir) {
        $libdir = $this->dirroot . $libdir;
    }
    if (!is_dir($libdir)) {
        $this->error("set_libdir: $libdir is not a directory.");
        return false;
    }
    $this->libdir = $libdir . "/";
    return true;
}

/**
* The method to set libwww
* @access public
* @return void
*/
function set_libwww($libwww) {
    if (substr($libwww, -1) != "/") {
        $libwww .= "/";
    }
    $this->libwww = $libwww;
}

/**
* The method to set the tpldir directory
* @access public
* @return boolean
*/
function set_tpldir($tpldir) {
    if (substr($tpldir, -1) == "/") {
        $tpldir = substr($tpldir, 0, strlen($tpldir)-1);
    }
    if (str_replace("/", "", $tpldir) == $tpldir) {
        $tpldir = $this->dirroot . $tpldir;
    }
    if (!is_dir($tpldir)) {
        $this->error("set_tpldir: $tpldir is not a directory.");
        return false;
    }
    $this->tpldir = $tpldir . "/";
    return true;
}

/**
* The method to set the imgdir directory
* @access public
* @return boolean
*/
function set_imgdir($imgdir) {
    if (substr($imgdir, -1) == "/") {
        $imgdir = substr($imgdir, 0, strlen($imgdir)-1);
    }
    if (str_replace("/", "", $imgdir) == $imgdir) {
        $imgdir = $this->dirroot . $imgdir;
    }
    if (!is_dir($imgdir)) {
        $this->error("set_imgdir: $imgdir is not a directory.");
        return false;
    }
    $this->imgdir = $imgdir . "/";
    return true;
}

/**
* The method to set imgwww
* @access public
* @return void
*/
function set_imgwww($imgwww) {
    if (substr($imgwww, -1) != "/") {
        $imgwww .= "/";
    }
    $this->imgwww = $imgwww;
}

/**
* The method to set horizontal_menu_tpl
* @access public
* @return boolean
*/
function set_horizontal_menu_tpl($horizontal_menu_tpl) {
    if (str_replace("/", "", $horizontal_menu_tpl) == $horizontal_menu_tpl) {
        $horizontal_menu_tpl = $this->tpldir . $horizontal_menu_tpl;
    }
    if (!file_exists($horizontal_menu_tpl)) {
        $this->error("set_horizontal_menu_tpl: file $horizontal_menu_tpl does not exist.");
        return false;
    }
    $this->horizontal_menu_tpl = $horizontal_menu_tpl;
    return true;
}

/**
* The method to set vertical_menu_tpl
* @access public
* @return boolean
*/
function set_vertical_menu_tpl($vertical_menu_tpl) {
    if (str_replace("/", "", $vertical_menu_tpl) == $vertical_menu_tpl) {
        $vertical_menu_tpl = $this->tpldir . $vertical_menu_tpl;
    }
    if (!file_exists($vertical_menu_tpl)) {
        $this->error("set_vertical_menu_tpl: file $vertical_menu_tpl does not exist.");
        return false;
    }
    $this->vertical_menu_tpl = $vertical_menu_tpl;
    return true;
}

/**
* The method to set sub_menu_tpl
* @access public
* @return boolean
*/
function set_sub_menu_tpl($sub_menu_tpl) {
    if (str_replace("/", "", $sub_menu_tpl) == $sub_menu_tpl) {
        $sub_menu_tpl = $this->tpldir . $sub_menu_tpl;
    }
    if (!file_exists($sub_menu_tpl)) {
        $this->error("set_sub_menu_tpl: file $sub_menu_tpl does not exist.");
        return false;
    }
    $this->sub_menu_tpl = $sub_menu_tpl;
    return true;
}

/**
* A method to set forward_arrow
* @access public
* @param string $forward_arrow the forward arrow HTML code
* @return void
*/
function set_forward_arrow($forward_arrow) {
    $this->forward_arrow = $forward_arrow;
}

/**
* The method to set an image to be used for the forward arrow
* @access public
* @param string $forward_arrow_img the forward arrow image filename
* @return boolean
*/
function set_forward_arrow_img($forward_arrow_img) {
    if (!file_exists($this->imgdir . $forward_arrow_img)) {
        $this->error("set_forward_arrow_img: file " . $this->imgdir . $forward_arrow_img . " does not exist.");
        return false;
    }
    $foobar = getimagesize($this->imgdir . $forward_arrow_img);
    $this->forward_arrow = " <img src=\"" . $this->imgwww . $forward_arrow_img . "\" width=\"" . $foobar[0] . "\" height=\"" . $foobar[1] . "\" border=\"0\" alt=\" >>\">";
    return true;
}

/**
* A method to set down_arrow
* @access public
* @param string $down_arrow the down arrow HTML code
* @return void
*/
function set_down_arrow($down_arrow) {
    $this->down_arrow = $down_arrow;
}

/**
* The method to set an image to be used for the down arrow
* @access public
* @param string $down_arrow_img the down arrow image filename
* @return boolean
*/
function set_down_arrow_img($down_arrow_img) {
    if (!file_exists($this->imgdir . $down_arrow_img)) {
        $this->error("set_down_arrow_img: file " . $this->imgdir . $down_arrow_img . " does not exist.");
        return false;
    }
    $foobar = getimagesize($this->imgdir . $down_arrow_img);
    $this->down_arrow = " <img src=\"" . $this->imgwww . $down_arrow_img . "\" width=\"" . $foobar[0] . "\" height=\"" . $foobar[1] . "\" border=\"0\" alt=\" >>\" />";
    return true;
}

/**
* The method to read the menu structure from a file
* @access public
* @param string $tree_file the menu structure file
* @return boolean
*/
function set_menu_structure_file($tree_file) {
    if (!($fd = fopen($tree_file, "r"))) {
        $this->error("set_menu_structure_file: unable to open file $tree_file.");
        return false;
    }
    $this->menu_structure = "";
    while ($buffer = fgets($fd, 4096)) {
        $buffer = ereg_replace(chr(13), "", $buffer);    // Microsoft Stupidity Suppression
        $this->menu_structure .= $buffer;
    }
    fclose($fd);
    if ($this->menu_structure == "") {
        $this->error("set_menu_structure_file: $tree_file is empty.");
        return false;
    }
    return true;
}

/**
* The method to set the menu structure passing it through a string
* @access public
* @param string $tree_string the menu structure string
* @return boolean
*/
function set_menu_structure_string($tree_string) {
    $this->menu_structure = ereg_replace(chr(13), "", $tree_string);    // Microsoft Stupidity Suppression
    if ($this->menu_structure == "") {
        $this->error("set_menu_structure_string: empty string.");
        return false;
    }
    return true;
}

/**
* The method to set the value of separator
* @access public
* @return void
*/
function set_separator($separator) {
    $this->separator = $separator;
}

/**
* The method to parse the current menu structure and correspondingly update related variables
* @access public
* @param string $menu_name the name to be attributed to the menu
*   whose structure has to be parsed
* @return void
*/
function parse_menu_structure(
    $menu_name = ""    // non consistent default...
    ) {
    $this->max_level[$menu_name] = 0;
    $this->first_level_cnt[$menu_name] = 0;
    $this->first_item[$menu_name] = $this->nodes_count + 1;
    $cnt = $this->first_item[$menu_name];
    $menu_structure = $this->menu_structure;

    /* *********************************************** */
    /* Partially based on a piece of code taken from   */
    /* TreeMenu 1.1 - Bjorge Dijkstra (bjorge@gmx.net) */
    /* *********************************************** */

    while ($menu_structure != "") {
        $before_cr = strcspn($menu_structure, "\n");
        $buffer = substr($menu_structure, 0, $before_cr);
        $menu_structure = substr($menu_structure, $before_cr+1);
        if (substr($buffer, 0, 1) != "#") {    // non commented item line...
            $tmp = rtrim($buffer);
            $node = explode($this->separator, $tmp);
            for ($i=count($node); $i<=5; $i++) {
                $node[$i] = "";
            }
            $this->tree[$cnt]["level"] = strlen($node[0]);
            $this->tree[$cnt]["child_of_root_node"] = ($this->tree[$cnt]["level"] == 1);
            $this->tree[$cnt]["text"] = stripslashes($node[1]);
            $this->tree[$cnt]["layer_label"] = "L" . $cnt;
            $this->tree[$cnt]["link"] = (ereg_replace(" ", "", $node[2]) == "") ? "#" : $node[2];
            $this->tree[$cnt]["title"] = ($node[3] == "") ? "" : " title=\"" . addslashes($node[3]) . "\"";
            $fooimg = $this->imgdir . $node[4];
            if ($node[4] == "" || !(file_exists($fooimg))) {
                $this->tree[$cnt]["icon"] = "";
            } else {
                $foobar = getimagesize($fooimg);
                $this->tree[$cnt]["icon"] = "<img src=\"" . $this->imgwww . $node[4] . "\" width=\"" . $foobar[0] . "\" height=\"" . $foobar[1] . "\" border=\"0\" alt=\"O\">";
            }
            $this->tree[$cnt]["target"] = ($node[5] == "") ? "" : " target=\"" . $node[5] . "\"";
            $this->tree[$cnt]["id"] = ($node[6] == "") ? "" : $node[6];
            $this->tree[$cnt]["branchparent"] = ($node[7] == "") ? "" : $node[7];
            $this->max_level[$menu_name] = max($this->max_level[$menu_name], $this->tree[$cnt]["level"]);
            if ($this->tree[$cnt]["level"] == 1) {
                $this->first_level_cnt[$menu_name]++;
            }
            $cnt++;
        }
    }

    /* *********************************************** */
    $this->last_item[$menu_name] = count($this->tree);
    $this->nodes_count = $this->last_item[$menu_name];
    $this->tree[$this->last_item[$menu_name]+1]["level"] = 0;
    DEBUG_out(4,"debug4","....parsed menu structure<br>li:".$this->last_item[$menu_name]." ".$this->nodes_count." ");
}


/**
* The method to parse the current menu structure and correspondingly update related variables
* @access public
* @param array $tree_array the array containingthe tree-data
*   whose structure has to be parsed
* @return void
*/
function array2menu_structure($menu_name="",$tree_array = 0) {
    if (!is_array($tree_array)) return;
    
    $this->max_level[$menu_name] = 0;
    $this->first_level_cnt[$menu_name] = 0;
    $this->first_item[$menu_name] = $this->nodes_count + 1;
    $cnt = $this->first_item[$menu_name];
    
    foreach($tree_array as $key => $tree_value) {

            $this->tree[$cnt]["level"] = $tree_value[0];
            $this->tree[$cnt]["child_of_root_node"] = ($this->tree[$cnt]["level"] == 1);
            $this->tree[$cnt]["text"] = stripslashes($tree_value[1]);
            $this->tree[$cnt]["layer_label"] = "L" . $cnt;
            $this->tree[$cnt]["link"] = (ereg_replace(" ", "", $tree_value[2]) == "") ? "#" : $tree_value[2];
            $this->tree[$cnt]["title"] = ($tree_value[8] == "") ? "" : " title=\"" . addslashes($tree_value[8]) . "\"";
            $fooimg = $this->imgdir . $tree_value[6];
            if ($tree_value[6] == "" || !(file_exists($fooimg))) {
                $this->tree[$cnt]["icon"] = "";
                $this->tree[$cnt]["file"] = "";
            } else {
                $foobar = getimagesize($fooimg);
                $this->tree[$cnt]["icon"] = "<img src=\"" . $this->imgwww . $tree_value[6] . "\" name=\"".$tree_value[6]."\" width=\"" . $foobar[0] . "\" height=\"" . $foobar[1] . "\" border=\"0\" alt=\"".stripslashes($tree_value[1])."\">";
                $this->tree[$cnt]["file"] = $tree_value[6];
            }
            //$this->tree[$cnt]["target"] = ($node[5] == "") ? "" : " target=\"" . $node[5] . "\"";
            $this->tree[$cnt]["target"] = "";
            $this->tree[$cnt]["id"] = $tree_value[3];
            $this->max_level[$menu_name] = max($this->max_level[$menu_name], $this->tree[$cnt]["level"]);
            if ($this->tree[$cnt]["level"] == 1) {
                $this->first_level_cnt[$menu_name]++;
            }
            $cnt++;
    }
    

    /* *********************************************** */
    $this->last_item[$menu_name] = count($this->tree);
    $this->nodes_count = $this->last_item[$menu_name];
    $this->tree[$this->last_item[$menu_name]+1]["level"] = 0;
    DEBUG_out(4,"debug4","....parsed menu structure<br>li:".print_array($this->tree));
}


/**
* A method providing parsing needed both for horizontal and vertical menus
* @access private
* @param string $menu_name the name of the menu for which the parsing
*   has to be performed
* @return void
*/
function parse_common(
    $menu_name = ""    // non consistent default...
    ) {
    for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {    // this counter scans all nodes of the new menu
        $current_node[$this->tree[$cnt]["level"]] = $cnt;
        if ($this->tree[$cnt]["level"] > 1) {
            $this->tree[$cnt]["father_node"] = $current_node[$this->tree[$cnt]["level"]-1];
        }
        $this->father .= ($this->tree[$cnt]["child_of_root_node"]) ? "" : "father['". $this->tree[$cnt]["layer_label"] ."'] = \"" . $this->tree[$this->tree[$cnt]["father_node"]]["layer_label"] . "\";\n";
        $this->tree[$cnt]["not_a_leaf"] = ($this->tree[$cnt+1]["level"]>$this->tree[$cnt]["level"] && $cnt<$this->last_item[$menu_name]);
        // if the above condition is true, the node is not a leaf,
        // hence it has at least a child; if it is false, the node is a leaf
        if ($this->tree[$cnt]["not_a_leaf"]) {
            // initialize the corresponding layer content trought a void string
            $this->tree[$cnt]["layer_content"] = "";
            // the new layer is accounted for in the layers list
            $this->numl++;
            $this->listl .= "listl[" . $this->numl . "] = \"" . $this->tree[$cnt]["layer_label"] . "\";\n";
        }
/*
        if ($this->tree[$cnt]["not_a_leaf"]) {
            $this->tree[$cnt]["link"] = "#";
*/
    }
    DEBUG_out(4,"debug4",$this->father);
}

/**
* A method needed to update the footer both for horizontal and vertical menus
* @access private
* @param string $menu_name the name of the menu for which the updating
*   has to be performed
* @return void
*/
function update_footer(
    $menu_name = ""    // non consistent default...
    ) {
    $t = new Template();
    $t->set_file("page", $this->sub_menu_tpl);
    $t->set_block("page", "sub_menu_cell", "sub_menu_cell_blck");
    $t->set_var("sub_menu_cell_blck", "");
    $t->set_var("abscissa_step", $this->abscissa_step);

    for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {
        if ($this->tree[$cnt]["not_a_leaf"]) {
            $this->footer .= "\n<div id=\"" . $this->tree[$cnt]["layer_label"] . "\" style=\"position: absolute; left: 0; top: 0; visibility: hidden; z-index:20;\">\n";
            $t->set_var(array(
                "layer_title"        => $this->tree[$cnt]["text"],
                "sub_menu_cell_blck"    => $this->tree[$cnt]["layer_content"]
            ));
            $this->footer .= $t->parse("out", "page");
            $this->footer .= "</div>\n\n";
        }
    }
}

/**
* Method to preparare a horizontal menu.
*
* This method processes items of a menu to prepare the corresponding
* horizontal menu code updating many variables; it returns the code
* of the corresponding first_level_menu
*
* @access public
* @param string $menu_name the name of the menu whose items have to be processed
* @param integer $ordinate_margin margin (in pixels) to set the position
*   of a layer a bit above the ordinate of the "father" link
* @return string
*/
function new_horizontal_menu(
    $menu_name = "",    // non consistent default...
    $ordinate_margin = 12
    ) {

    $this->parse_common($menu_name);

    $t = new Template();
    $t->set_file("page", $this->horizontal_menu_tpl);
    $t->set_block("page", "horizontal_menu_cell", "horizontal_menu_cell_blck");
    $t->set_var("horizontal_menu_cell_blck", "");
        $t->set_block("horizontal_menu_cell", "cell_link", "cell_link_blck");
        $t->set_var("cell_link_blck", "");

    $t_sub = new Template();
    $t_sub->set_file("page", $this->sub_menu_tpl);
    $t_sub->set_block("page", "sub_menu_cell", "sub_menu_cell_blck");

    $this->first_level_menu[$menu_name] = "";

    $foobar = $this->first_item[$menu_name];
    $this->moveLayers .= "\tvar " . $menu_name . "TOP = getoffsettop('" . $menu_name . "L" . $foobar . "');\n";
    $this->moveLayers .= "\tvar " . $menu_name . "HEIGHT = getoffsetheight('" . $menu_name . "L" . $foobar . "');\n";

    for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {    // this counter scans all nodes of the new menu
    	$imagemouseover="";
        if($this->tree[$cnt]["file"]>""){ 
                $img_file=explode(".",$this->tree[$cnt]["file"]);
                $imagemouseover="MM_swapImage('".$this->tree[$cnt]["file"]."','','".$this->imgwww.$img_file[0]."_over.".$img_file[1]."',1);";
                }
        if ($this->tree[$cnt]["not_a_leaf"]) {
            // geometrical parameters are assigned to the new layer, related to the above mentioned children
            if ($this->tree[$cnt]["child_of_root_node"]) {
                $this->moveLayers .= "\tsetleft('" . $this->tree[$cnt]["layer_label"] . "', getoffsetleft('" . $menu_name . $this->tree[$cnt]["layer_label"] . "'));\n";
                $this->tree[$cnt]["arrow"] = $this->down_arrow;
            } else {
                $this->tree[$cnt]["arrow"] = $this->forward_arrow;
            }
            if ($this->tree[$cnt]["child_of_root_node"]) {
                $this->moveLayers .= "\tsettop('" . $this->tree[$cnt]["layer_label"] . "', "  . $menu_name . "TOP + " . $menu_name . "HEIGHT);\n";
            }
            $this->moveLayers .= "\tif (IE4) setwidth('" . $this->tree[$cnt]["layer_label"] . "'," . $this->abscissa_step . ");\n";
        } else {
            $this->tree[$cnt]["arrow"] = "";
        }
        if ($this->tree[$cnt]["id"]==$GLOBALS['BranchParent']) $this->tree[$cnt]["selected"]="selected";else $this->tree[$cnt]["selected"]="";
        if ($this->tree[$cnt]["id"]==$GLOBALS['mid']) $this->tree[$cnt]["selected"]="selected";else $this->tree[$cnt]["selected"]="";
        if ($this->tree[$cnt]["child_of_root_node"]) {
            if ($this->tree[$cnt]["not_a_leaf"]) {
                
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"popUp('" . $this->tree[$cnt]["layer_label"] . "');".$imagemouseover."\"";
            } else {
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"shutdown();\"";
            }
            $t->set_var(array(
                "icon"        => $this->tree[$cnt]["icon"],
                "link"        => $this->tree[$cnt]["link"],
                "onmouseover"    => $this->tree[$cnt]["onmouseover"],
                "title"        => $this->tree[$cnt]["title"],
                "target"    => $this->tree[$cnt]["target"],
                "text"        => $this->tree[$cnt]["text"],
                "arrow"        => $this->tree[$cnt]["arrow"],
                "selected"        => $this->tree[$cnt]["selected"]
            ));
            $foobar = $t->parse("cell_link_blck", "cell_link", false);
            $foobar =
            "<div id=\"" . $menu_name . $this->tree[$cnt]["layer_label"] . "\" style=\"position: relative; visibility: visible;\">\n" .
            "<script language=\"JavaScript\" type=\"text/javascript\">\n" .
            "<!--\n" .
            "if (IE) fixieflm(\"" . $menu_name . $this->tree[$cnt]["layer_label"] . "\");\n" .
            "// -->\n" .
            "</script>" .
            $foobar . "\n" .
            "</div>";
            $t->set_var(array(
                "cellwidth"        => $this->abscissa_step,
                "cell_link_blck"    => $foobar
            ));
            $t->parse("horizontal_menu_cell_blck", "horizontal_menu_cell", true);
        } else {
            if ($this->tree[$cnt]["not_a_leaf"]) {
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"moveLayerY('" . $this->tree[$cnt]["layer_label"] . "', " . $ordinate_margin . ") ; popUp('" . $this->tree[$cnt]["layer_label"] . "');".$imagemouseover."\"";
            } else {
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"popUp('" . $this->tree[$this->tree[$cnt]["father_node"]]["layer_label"] . "');\"";
            }
            if ($this->tree[$cnt]["id"]==$GLOBALS['mid']) $this->tree[$cnt]["selected"]="selected";
            $t_sub->set_var(array(
                "ordinate_step"    => $this->ordinate_step,
                "icon"        => $this->tree[$cnt]["icon"],
                "link"        => $this->tree[$cnt]["link"],
                "refid"        => " id=\"ref" . $this->tree[$cnt]["layer_label"] . "\"",
                "onmouseover"    => $this->tree[$cnt]["onmouseover"],
                "title"        => $this->tree[$cnt]["title"],
                "target"    => $this->tree[$cnt]["target"],
                "text"        => $this->tree[$cnt]["text"],
                "arrow"        => $this->tree[$cnt]["arrow"],
                "selected"        => $this->tree[$cnt]["selected"]
            ));
            $this->tree[$this->tree[$cnt]["father_node"]]["layer_content"] .= $t_sub->parse("sub_menu_cell_blck", "sub_menu_cell", false);
        }
    }    // end of the "for" cycle scanning all nodes

    $foobar = $this->first_level_cnt[$menu_name] * $this->abscissa_step;
    $t->set_var("menuwidth", $foobar);
    $t->set_var(array(
        "layer_label"    => $menu_name,
        "menubody"    => $this->first_level_menu[$menu_name]
    ));
    $this->first_level_menu[$menu_name] = $t->parse("out", "page");

    $this->update_footer($menu_name);

    return $this->first_level_menu[$menu_name];
}

/**
* Method to preparare a vertical menu.
*
* This method processes items of a menu to prepare the corresponding
* vertical menu code updating many variables; it returns the code
* of the corresponding first_level_menu
*
* @access public
* @param string $menu_name the name of the menu whose items have to be processed
* @param integer $ordinate_margin margin (in pixels) to set the position
*   of a layer a bit above the ordinate of the "father" link
* @return string
*/
function new_vertical_menu(
    $menu_name = "",    // non consistent default...
    $ordinate_margin = 12
    ) {

    $this->parse_common($menu_name);

    $t = new Template();
    $t->set_file("page", $this->vertical_menu_tpl);
    $t->set_block("page", "vertical_menu_table", "vertical_menu_table_blck");
    $t->set_var("vertical_menu_table_blck", "");
        $t->set_block("vertical_menu_table", "vertical_menu_cell", "vertical_menu_cell_blck");
        $t->set_var("vertical_menu_cell_blck", "");

    $t_sub = new Template();
    $t_sub->set_file("page", $this->sub_menu_tpl);
    $t_sub->set_block("page", "sub_menu_cell", "sub_menu_cell_blck");

    $this->first_level_menu[$menu_name] = "";

    $this->moveLayers .= "\tvar " . $menu_name . "TOP = getoffsettop('" . $menu_name . "');\n";
    $this->moveLayers .= "\tvar " . $menu_name . "LEFT = getoffsetleft('" . $menu_name . "');\n";
    $this->moveLayers .= "\tvar " . $menu_name . "WIDTH = getoffsetwidth('" . $menu_name . "');\n";
    IF ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'].=$cnt." ".print_array($this->tree);
    for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {    // this counter scans all nodes of the new menu
    if($this->tree[$cnt]["file"]>""){ 
                $img_file=explode(".",$this->tree[$cnt]["file"]);
                $imagemouseover="MM_swapImage('".$this->tree[$cnt]["file"]."','','".$this->imgwww.$img_file[0]."_over.".$img_file[1]."',1);";
                }
        if ($this->tree[$cnt]["not_a_leaf"]) {
            // geometrical parameters are assigned to the new layer, related to the above mentioned children
            if ($this->tree[$cnt]["child_of_root_node"]) {
                $this->moveLayers .= "\tsetleft('" . $this->tree[$cnt]["layer_label"] . "', " . $menu_name . "LEFT + " . $menu_name . "WIDTH);\n";
            }
            $this->tree[$cnt]["arrow"] = $this->forward_arrow;
            $this->moveLayers .= "\tif (IE4) setwidth('" . $this->tree[$cnt]["layer_label"] . "'," . $this->abscissa_step . ");\n";
        } else {
            $this->tree[$cnt]["arrow"] = "";
        }

        if ($this->tree[$cnt]["child_of_root_node"]) {
            if ($this->tree[$cnt]["not_a_leaf"]) {
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"moveLayerY('" . $this->tree[$cnt]["layer_label"] . "', " . $ordinate_margin . ") ; popUp('" . $this->tree[$cnt]["layer_label"] . "');".$imagemouseover."\" onMouseOut=\"MM_swapImgRestore()\"";
            } else {
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"shutdown();\"";
            }
            $t->set_var(array(
                "ordinate_step"    => $this->ordinate_step,
                "icon"        => $this->tree[$cnt]["icon"],
                "link"        => $this->tree[$cnt]["link"],
                "refid"        => " id=\"ref" . $this->tree[$cnt]["layer_label"] . "\"",
                "onmouseover"    => $this->tree[$cnt]["onmouseover"],
                "title"        => $this->tree[$cnt]["title"],
                "target"    => $this->tree[$cnt]["target"],
                "text"        => $this->tree[$cnt]["text"],
                "arrow"        => $this->tree[$cnt]["arrow"]
            ));
            $this->first_level_menu[$menu_name] .= $t->parse("vertical_menu_cell_blck", "vertical_menu_cell", false);
        } else {
            if ($this->tree[$cnt]["not_a_leaf"]) {
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"moveLayerY('" . $this->tree[$cnt]["layer_label"] . "', " . $ordinate_margin . ") ; popUp('" . $this->tree[$cnt]["layer_label"] . "');".$imagemouseover."\"onMouseOut=\"MM_swapImgRestore()\"";
            } else {
                $this->tree[$cnt]["onmouseover"] = " onmouseover=\"popUp('" . $this->tree[$this->tree[$cnt]["father_node"]]["layer_label"] . "');\"";
            }
            $t_sub->set_var(array(
                "ordinate_step"    => $this->ordinate_step,
                "icon"        => $this->tree[$cnt]["icon"],
                "link"        => $this->tree[$cnt]["link"],
                "refid"        => " id=\"ref" . $this->tree[$cnt]["layer_label"] . "\"",
                "onmouseover"    => $this->tree[$cnt]["onmouseover"],
                "title"        => $this->tree[$cnt]["title"],
                "target"    => $this->tree[$cnt]["target"],
                "text"        => $this->tree[$cnt]["text"],
                "arrow"        => $this->tree[$cnt]["arrow"]
            ));
            $this->tree[$this->tree[$cnt]["father_node"]]["layer_content"] .= $t_sub->parse("sub_menu_cell_blck", "sub_menu_cell", false);
        }
    }    // end of the "for" cycle scanning all nodes

    $t->set_var("vertical_menu_cell_blck", $this->first_level_menu[$menu_name]);
    $this->first_level_menu[$menu_name] = $t->parse("vertical_menu_table_blck", "vertical_menu_table", false);
    $this->first_level_menu[$menu_name] =
    "<div id=\"" . $menu_name . "\" style=\"position: relative; z-index:2;\">\n" .
    "<script language=\"JavaScript\" type=\"text/javascript\">\n" .
    "<!--\n" .
    "if (IE) fixieflm(\"" . $menu_name . "\");\n" .
    "// -->\n" .
    "</script>" .
    $this->first_level_menu[$menu_name] . "\n" .
    "</div>";
    $t->set_file("page", $this->vertical_menu_tpl);
    $t->set_var("abscissa_step", $this->abscissa_step);
    $t->set_var(array(
        "layer_label"        => $menu_name,
        "vertical_menu_table_blck"    => $this->first_level_menu[$menu_name]
    ));
    $this->first_level_menu[$menu_name] = $t->parse("out", "page");

    $this->update_footer($menu_name);

    return $this->first_level_menu[$menu_name];
}

/**
* Method to prepare the header.
*
* This method obtains the header using collected informations
* and the suited JavaScript template; it returns the code of the header
*
* @access public
* @return string
*/
function make_header() {
    $t = new Template();
    $t->set_file(array(
        "page"            => $this->libdir . "layersmenu-header.ijs",
        "browser_detection"    => $this->libdir . "layersmenu-browser_detection.js"
    ));
    $t->set_var(array(
        "package_name"    => $this->package_name,
        "version"    => $this->version,
        "copyright"    => $this->copyright,
        "author"    => $this->author,
        "thresholdY"    => $this->thresholdY,
        "abscissa_step"    => $this->abscissa_step,
        "libwww"    => $this->libwww,
        "listl"        => $this->listl,
        "numl"        => $this->numl,
        "nodes_count"    => $this->nodes_count,
        "father"    => $this->father,
        "moveLayers"    => $this->moveLayers
    ));
    $this->header = $t->parse("out", array("browser_detection", "page"));
    return $this->header;
}

/**
* Method that returns the code of the header
* @access public
* @return string
*/
function get_header() {
    return $this->header;
}

/**
* Method that prints the code of the header
* @access public
* @return void
*/
function print_header() {
    $this->make_header();
    print $this->header;
}

/**
* Method that returns the code of the requested first_level_menu
* @access public
* @param string $menu_name the name of the menu whose first_level_menu
*   has to be returned
* @return string
*/
function get_menu($menu_name) {
    return $this->first_level_menu[$menu_name];
}

/**
* Method that prints the code of the requested first_level_menu
* @access public
* @param string $menu_name the name of the menu whose first_level_menu
*   has to be printed
* @return void
*/
function print_menu($menu_name) {
    print $this->first_level_menu[$menu_name];
}

/**
* Method to prepare the footer.
*
* This method obtains the footer using collected informations
* and the suited JavaScript template; it returns the code of the footer
*
* @access public
* @return string
*/
function make_footer() {
    $t = new Template();
    $t->set_file("page", $this->libdir . "layersmenu-footer.ijs");
    $t->set_var(array(
        "package_name"    => $this->package_name,
        "version"    => $this->version,
        "copyright"    => $this->copyright,
        "author"    => $this->author,
        "footer"    => $this->footer
        
    ));
    $this->footer = $t->parse("out", "page");
    return $this->footer;
}

/**
* Method that returns the code of the footer
* @access public
* @return string
*/
function get_footer() {
    return $this->footer;
}

/**
* Method that prints the code of the footer
* @access public
* @return void
*/
function print_footer() {
    $this->make_footer();
    print $this->footer;
}

/**
* Method to handle errors
* @access private
* @param string $errormsg the error message
* @return void
*/
function error($errormsg) {
    print "<b>LayersMenu Error:</b> " . $errormsg . "<br>\n";
    if ($this->halt_on_error == "yes") {
        die("<b>Halted.</b><br>\n");
    }
}

} /* END OF CLASS */

?>