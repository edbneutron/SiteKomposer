<?php
// PHP Layers Menu 2.2.0 (C) 2001-2002 Marco Pratesi (marco at telug dot it)

/**
* This is an extension of the base class of the PHP Layers Menu system.
*
* It provides other menu types, that to do not require JavaScript to work.
*
* @version 2.2.0
* @package PHPLayersMenu
*/
class XLayersMenu extends LayersMenu {

/**
* The character used for the Tree Menu in the menu structure format to separate fields of each item
* @access private
* @var string
*/
var $tree_menu_separator;
/**
* Width of images used for the Tree Menu
* @access private
* @var integer
*/
var $tree_menu_images_width;
/**
* An array where we store the Tree Menu code for each menu
* @access private
* @var array
*/
var $tree_menu;

/**
* The character used for the Plain Menu in the menu structure format to separate fields of each item
* @access private
* @var string
*/
var $plain_menu_separator;
/**
* The template to be used for the Plain Menu
*/
var $plain_menu_tpl;
/**
* An array where we store the Plain Menu code for each menu
* @access private
* @var array
*/
var $plain_menu;

/**
* The character used for the Horizontal Plain Menu in the menu structure format to separate fields of each item
* @access private
* @var string
*/
var $horizontal_plain_menu_separator;
/**
* The template to be used for the Horizontal Plain Menu
*/
var $horizontal_plain_menu_tpl;
/**
* An array where we store the Horizontal Plain Menu code for each menu
* @access private
* @var array
*/
var $horizontal_plain_menu;

/**
* The constructor method; it initializates some variables
* @return void
*/
function XLayersMenu() {
	$this->LayersMenu();
	$this->tree_menu_separator = "|";
	$this->tree_menu_images_width = 20;
	$this->tree_menu = array();

	$this->plain_menu_tpl = $this->dirroot . $this->tpldir . "layersmenu-plain_menu.ihtml";
	$this->plain_menu_separator = "|";
	$this->plain_menu = array();

	$this->horizontal_plain_menu_tpl = $this->dirroot . $this->tpldir . "layersmenu-horizontal_plain_menu.ihtml";
	$this->horizontal_plain_menu_separator = "|";
	$this->horizontal_plain_menu = array();
}

/**
* The method to set the value of separator for the Tree Menu
* @access public
* @return void
*/
function set_tree_menu_separator($tree_menu_separator) {
	$this->tree_menu_separator = $tree_menu_separator;
}

/**
* The method to set the width of images used for the Tree Menu
* @access public
* @return void
*/
function set_tree_menu_images_width($tree_menu_images_width) {
	$this->tree_menu_images_width = $tree_menu_images_width;
}

/**
* Method to prepare a new Tree Menu.
*
* This method processes items of a menu and parameters submitted
* through GET (i.e. nodes to be expanded) to prepare and return
* the corresponding Tree Menu code.
*
* @access public
* @param string $menu_name the name of the menu whose items have to be processed
* @return string
*/
function new_tree_menu(
	$menu_name = ""	// non consistent default...
	) {
	if (!isset($_SERVER)) {	// Needed for compatibility with PHP < 4.1
		global $HTTP_SERVER_VARS;
		$_SERVER = $HTTP_SERVER_VARS;
	}
	if (!isset($_GET)) {	// Needed for compatibility with PHP < 4.1
		global $HTTP_GET_VARS;
		$_GET = $HTTP_GET_VARS;
	}
	global $HTTPS;
	$protocol = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || (isset($HTTPS) && $HTTPS == "on")) ? "https://" : "http://";
	if (isset($_SERVER["SERVER_NAME"])) {
		$this_host = $_SERVER["SERVER_NAME"];
	} else {	// Needed for compatibility with PHP 3
		global $SERVER_NAME;
		if (isset($SERVER_NAME)) {
			$this_host = $SERVER_NAME;
		}
	}
	if (isset($_SERVER["SCRIPT_NAME"])) {
		$me = $_SERVER["SCRIPT_NAME"];
	} else if (isset($_SERVER["REQUEST_URI"])) {
		$me = $_SERVER["REQUEST_URI"];
	} else if (isset($_SERVER["PHP_SELF"])) {
		$me = $_SERVER["PHP_SELF"];
	} else if (isset($_SERVER["PATH_INFO"])) {
		$me = $_SERVER["PATH_INFO"];
	} else {	// Needed for compatibility with PHP 3
		global $SCRIPT_NAME, $PATH_INFO;
		if (isset($SCRIPT_NAME)) {
			$me = $SCRIPT_NAME;
		} else if (isset($PATH_INFO)) {
			$me = $PATH_INFO;
		}
	}
	$url = $protocol . $this_host . $me;
	$query = "";
	reset($_GET);
	while (list($key, $value) = each($_GET)) {
		if ($key != "p" && $value != "") {
			$query .= "&amp;" . $key . "=" . $value;
		}
	}
	if ($query != "") {
		$query = "?" . substr($query, 5) . "&amp;p=";
	} else {
		$query = "?p=";
	}

	if (!isset($_GET)) {	// Needed for compatibility with PHP < 4.1
		global $HTTP_GET_VARS;
		$_GET = $HTTP_GET_VARS;
	}
	$p = (isset($_GET["p"])) ? $_GET["p"] : "";

/* ********************************************************* */
/* Based on TreeMenu 1.1 by Bjorge Dijkstra (bjorge@gmx.net) */
/* ********************************************************* */
	$this->tree_menu[$menu_name] = "";

	$img_space		= $this->imgwww . "tree_space.png";
	$alt_space		= "  ";
	$img_vertline		= $this->imgwww . "tree_vertline.png";
	$alt_vertline		= "| ";
	$img_expand		= $this->imgwww . "tree_expand.png";
	$alt_expand		= "+-";
	$img_expand_corner	= $this->imgwww . "tree_expand_corner.png";
	$alt_expand_corner	= "+-";
	$img_collapse		= $this->imgwww . "tree_collapse.png";
	$alt_collapse		= "--";
	$img_collapse_corner	= $this->imgwww . "tree_collapse_corner.png";
	$alt_collapse_corner	= "--";
	$img_split		= $this->imgwww . "tree_split.png";
	$alt_split		= "|-";
	$img_corner		= $this->imgwww . "tree_corner.png";
	$alt_corner		= "`-";
	$img_folder_closed	= $this->imgwww . "tree_folder_closed.png";
	$alt_folder_closed	= "->";
	$img_folder_open	= $this->imgwww . "tree_folder_open.png";
	$alt_folder_open	= "->";
	$img_leaf		= $this->imgwww . "tree_leaf.png";
	$alt_leaf		= "->";

	for ($i=$this->first_item[$menu_name]; $i<=$this->last_item[$menu_name]; $i++) {
		$expand[$i] = 0;
		$visible[$i] = 0;
		$this->tree[$i]["last_item"] = 0;
	}
	for ($i=0; $i<=$this->max_level[$menu_name]; $i++) {
		$levels[$i] = 0;
	}

	// Get numbers of nodes to be expanded
	if ($p != "") {
		$explevels = explode($this->tree_menu_separator, $p);
		$explevels_count = count($explevels);
		for ($i=0; $i<$explevels_count; $i++) {
			$expand[$explevels[$i]] = 1;
		}
	}

	// Find last nodes of subtrees
	$last_level = $this->max_level;
	for ($i=$this->last_item[$menu_name]; $i>=$this->first_item[$menu_name]; $i--) {
		if ($this->tree[$i]["level"] < $last_level) {
			for ($j=$this->tree[$i]["level"]+1; $j<=$this->max_level[$menu_name]; $j++) {
				$levels[$j] = 0;
			}
		}
		if ($levels[$this->tree[$i]["level"]] == 0) {
			$levels[$this->tree[$i]["level"]] = 1;
			$this->tree[$i]["last_item"] = 1;
		} else {
			$this->tree[$i]["last_item"] = 0;
		}
		$last_level = $this->tree[$i]["level"];
	}

	// Determine visible nodes
	// all root nodes are always visible
	for ($i=$this->first_item[$menu_name]; $i<=$this->last_item[$menu_name]; $i++) {
		if ($this->tree[$i]["level"] == 1) {
			$visible[$i] = 1;
		}
	}
	if (isset($explevels)) {
		for ($i=0; $i<$explevels_count; $i++) {
			$n = $explevels[$i];
			if ($n >= $this->first_item[$menu_name] && $n <= $this->last_item[$menu_name] && $visible[$n] == 1 && $expand[$n] == 1) {
				$j = $n + 1;
				while ($j<=$this->last_item[$menu_name] && $this->tree[$j]["level"]>$this->tree[$n]["level"]) {
					if ($this->tree[$j]["level"] == $this->tree[$n]["level"]+1) {
						$visible[$j] = 1;
					}
					$j++;
				}
			}
		}
	}

	// Output nicely formatted tree
	for ($i=0; $i<$this->max_level[$menu_name]; $i++) {
		$levels[$i] = 1;
	}
	$this->tree_menu[$menu_name] .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n<tr>\n";
	for ($i=0; $i<$this->max_level[$menu_name] + 1; $i++) {
		$this->tree_menu[$menu_name] .= "<td width=\"" . $this->tree_menu_images_width . "\"></td>";
	}
	$this->tree_menu[$menu_name] .= "<td></td>\n</tr>\n";
	$max_visible_level = 0;
	for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {
		if ($visible[$cnt]) {
			$max_visible_level = max($max_visible_level, $this->tree[$cnt]["level"]);
		}
	}
	for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {
		if ($visible[$cnt]) {
			$this->tree_menu[$menu_name] .= "<tr>";

			// vertical lines from higher levels
			for ($i=0; $i<$this->tree[$cnt]["level"]-1; $i++) {
				if ($levels[$i] == 1) {
					$img = $img_vertline;
					$alt = $alt_vertline;
				} else {
					$img = $img_space;
					$alt = $alt_space;
				}
				$this->tree_menu[$menu_name] .= "<td><a name=\"" . $cnt . "\"></a><img src=\"" . $img . "\" width=\"" . $this->tree_menu_images_width . "\" border=\"0\" alt=\"" . $alt . "\" /></td>";
			}

			$not_a_leaf = $cnt<$this->last_item[$menu_name] && $this->tree[$cnt+1]["level"]>$this->tree[$cnt]["level"];

			if ($not_a_leaf) {
				// Create expand/collapse parameters
				$params = "";
				for ($i=$this->first_item[$menu_name]; $i<=$this->last_item[$menu_name]; $i++) {
					if ($expand[$i] == 1 && $cnt!= $i || ($expand[$i] == 0 && $cnt == $i)) {
						$params .= $this->tree_menu_separator . $i;
					}
				}
				if ($params != "") {
					$params = substr($params, 1);
				}
			}

			// corner at end of subtree or t-split
			if ($this->tree[$cnt]["last_item"] == 1) {
				if ($not_a_leaf) {
					if ($expand[$cnt] == 0) {
						$img = $img_expand_corner;
						$alt = $alt_expand_corner;
					} else {
						$img = $img_collapse_corner;
						$alt = $alt_collapse_corner;
					}
					$this->tree_menu[$menu_name] .= "<td><a href=\"" . $url . $query . $params . "#" . $cnt . "\"><img src=\"" . $img . "\" width=\"" . $this->tree_menu_images_width . "\" border=\"0\" alt=\"" . $alt . "\" /></a></td>";
				} else {
					$this->tree_menu[$menu_name] .= "<td><img src=\"" . $img_corner . "\" width=\"" . $this->tree_menu_images_width . "\" border=\"0\" alt=\"`-\" /></td>";
				}
				$levels[$this->tree[$cnt]["level"]-1] = 0;
			} else {
				if ($not_a_leaf) {
					if ($expand[$cnt] == 0) {
						$img = $img_expand;
						$alt = $alt_expand;
					} else {
						$img = $img_collapse;
						$alt = $alt_collapse;
					}
					$this->tree_menu[$menu_name] .= "<td><a href=\"" . $url . $query . $params . "#" . $cnt . "\"><img src=\"" . $img . "\" width=\"" . $this->tree_menu_images_width . "\" border=\"0\" alt=\"" . $alt . "\" /></a></td>";
				} else {
					$this->tree_menu[$menu_name] .= "<td><img src=\"" . $img_split . "\" width=\"" . $this->tree_menu_images_width . "\" border=\"0\" alt=\"|-\" /></td>";
				}
				$levels[$this->tree[$cnt]["level"]-1] = 1;
			}

			if ($this->tree[$cnt]["link"] == "") {
				$a_href_open = "";
				$a_href_close = "";
			} else {
				$a_href_open = "<a href=\"" . $this->tree[$cnt]["link"] . "\"" . $this->tree[$cnt]["title"] . $this->tree[$cnt]["target"] . ">";
				$a_href_close = "</a>";
			}

			if ($not_a_leaf) {
				if ($expand[$cnt] == 1) {
					$img = $img_folder_open;
					$alt = $alt_folder_open;
				} else {
					$img = $img_folder_closed;
					$alt = $alt_folder_closed;
				}
			} else {
				$img = $img_leaf;
				$alt = $alt_leaf;
			}
			$this->tree_menu[$menu_name] .= "<td>" . $a_href_open . "<img src=\"" . $img . "\" width=\"" . $this->tree_menu_images_width . "\" border=\"0\" alt=\"" . $alt . "\" />" . $a_href_close . "</td>";

			// output item text
			$foobar = $max_visible_level - $this->tree[$cnt]["level"] + 1;
			if ($foobar > 1) {
				$colspan = " colspan=\"" . $foobar . "\"";
			} else {
				$colspan = "";
			}
			$this->tree_menu[$menu_name] .= "<td class=\"normal\" nowrap=\"nowrap\"" . $colspan . ">" . $a_href_open . $this->tree[$cnt]["text"] . $a_href_close . "</td>";
			$this->tree_menu[$menu_name] .= "</tr>\n";
		}
	}
	$this->tree_menu[$menu_name] .= "</table>\n";
/* ********************************************************* */

	return $this->tree_menu[$menu_name];
}

/**
* Method that returns the code of the requested Tree Menu
* @access public
* @param string $menu_name the name of the menu whose Tree Menu code
*   has to be returned
* @return string
*/
function get_tree_menu($menu_name) {
	return $this->tree_menu[$menu_name];
}

/**
* Method that prints the code of the requested Tree Menu
* @access public
* @param string $menu_name the name of the menu whose Tree Menu code
*   has to be printed
* @return void
*/
function print_tree_menu($menu_name) {
	print $this->tree_menu[$menu_name];
}

/**
* The method to set the value of separator for the Plain Menu
* @access public
* @return void
*/
function set_plain_menu_separator($plain_menu_separator) {
	$this->plain_menu_separator = $plain_menu_separator;
}

/**
* The method to set plain_menu_tpl
* @access public
* @return boolean
*/
function set_plain_menu_tpl($plain_menu_tpl) {
	if (str_replace("/", "", $plain_menu_tpl) == $plain_menu_tpl) {
		$plain_menu_tpl = $this->tpldir . $plain_menu_tpl;
	}
	if (!file_exists($plain_menu_tpl)) {
		$this->error("set_plain_menu_tpl: file $plain_menu_tpl does not exist.");
		return false;
	}
	$this->plain_menu_tpl = $plain_menu_tpl;
	return true;
}

/**
* Method to prepare a new Plain Menu.
*
* This method processes items of a menu to prepare and return
* the corresponding Plain Menu code.
*
* @access public
* @param string $menu_name the name of the menu whose items have to be processed
* @return string
*/
function new_plain_menu(
	$menu_name = ""	// non consistent default...
	) {
	$plain_menu_blck = "";
	$t = new Template();
	$t->set_file("page", $this->plain_menu_tpl);
	$t->set_block("page", "plain_menu_cell", "plain_menu_cell_blck");
	$t->set_var("plain_menu_cell_blck", "");
	for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {
		$nbsp = "";
		for ($i=1; $i<$this->tree[$cnt]["level"]; $i++) {
			$nbsp .= "&nbsp;&nbsp;&nbsp;";
		}
		$t->set_var(array(
			"nbsp"		=> $nbsp,
			"link"		=> $this->tree[$cnt]["link"],
			"title"		=> $this->tree[$cnt]["title"],
			"target"	=> $this->tree[$cnt]["target"],
			"text"		=> $this->tree[$cnt]["text"]
		));
		$plain_menu_blck .= $t->parse("plain_menu_cell_blck", "plain_menu_cell", false);
	}
	$t->set_var("plain_menu_cell_blck", $plain_menu_blck);
	$this->plain_menu[$menu_name] = $t->parse("out", "page");

	return $this->plain_menu[$menu_name];
}

/**
* Method that returns the code of the requested Plain Menu
* @access public
* @param string $menu_name the name of the menu whose Plain Menu code
*   has to be returned
* @return string
*/
function get_plain_menu($menu_name) {
	return $this->plain_menu[$menu_name];
}

/**
* Method that prints the code of the requested Plain Menu
* @access public
* @param string $menu_name the name of the menu whose Plain Menu code
*   has to be printed
* @return void
*/
function print_plain_menu($menu_name) {
	print $this->plain_menu[$menu_name];
}

/**     
* The method to set the value of separator for the Horizontal Plain Menu
* @access public
* @return void          
*/
function set_horizontal_plain_menu_separator($horizontal_plain_menu_separator) {
	$this->horizontal_plain_menu_separator = $horizontal_plain_menu_separator;
}

/**
* The method to set horizontal_plain_menu_tpl
* @access public
* @return boolean
*/
function set_horizontal_plain_menu_tpl($horizontal_plain_menu_tpl) {
	if (str_replace("/", "", $horizontal_plain_menu_tpl) == $horizontal_plain_menu_tpl) {
		$horizontal_plain_menu_tpl = $this->tpldir . $horizontal_plain_menu_tpl;
	}
	if (!file_exists($horizontal_plain_menu_tpl)) {
		$this->error("set_horizontal_plain_menu_tpl: file $horizontal_plain_menu_tpl does not exist.");
		return false;
	}
	$this->horizontal_plain_menu_tpl = $horizontal_plain_menu_tpl;
	return true;
}

/**
* Method to prepare a new Horizontal Plain Menu.
*
* This method processes items of a menu to prepare and return
* the corresponding Horizontal Plain Menu code.
*
* @access public
* @param string $menu_name the name of the menu whose items have to be processed
* @return string
*/
function new_horizontal_plain_menu(
	$menu_name = ""	// non consistent default...
	) {
	$horizontal_plain_menu_blck = "";
	$t = new Template();
	$t->set_file("page", $this->horizontal_plain_menu_tpl);
	$t->set_block("page", "horizontal_plain_menu_cell", "horizontal_plain_menu_cell_blck");
	$t->set_var("horizontal_plain_menu_cell_blck", "");
		$t->set_block("horizontal_plain_menu_cell", "plain_menu_cell", "plain_menu_cell_blck");	
		$t->set_var("plain_menu_cell_blck", "");
	for ($cnt=$this->first_item[$menu_name]; $cnt<=$this->last_item[$menu_name]; $cnt++) {
		if ($this->tree[$cnt]["level"] == 1 && $cnt > $this->first_item[$menu_name]) {
			$t->parse("horizontal_plain_menu_cell_blck", "horizontal_plain_menu_cell", true);
			$t->set_var("plain_menu_cell_blck", "");
		}
		$nbsp = "";
		for ($i=1; $i<$this->tree[$cnt]["level"]; $i++) {
			$nbsp .= "&nbsp;&nbsp;&nbsp;";
		}
		$t->set_var(array(
			"nbsp"		=> $nbsp,
			"link"		=> $this->tree[$cnt]["link"],
			"title"		=> $this->tree[$cnt]["title"],
			"target"	=> $this->tree[$cnt]["target"],
			"text"		=> $this->tree[$cnt]["text"]
		));
		$t->parse("plain_menu_cell_blck", "plain_menu_cell", true);
	}
	$t->parse("horizontal_plain_menu_cell_blck", "horizontal_plain_menu_cell", true);
	$this->horizontal_plain_menu[$menu_name] = $t->parse("out", "page");

	return $this->horizontal_plain_menu[$menu_name];
}

/**
* Method that returns the code of the requested Horizontal Plain Menu
* @access public
* @param string $menu_name the name of the menu whose Horizontal Plain Menu code
*   has to be returned
* @return string
*/
function get_horizontal_plain_menu($menu_name) {
	return $this->horizontal_plain_menu[$menu_name];
}

/**
* Method that prints the code of the requested Horizontal Plain Menu
* @access public
* @param string $menu_name the name of the menu whose Horizontal Plain Menu code
*   has to be printed
* @return void
*/
function print_horizontal_plain_menu($menu_name) {
	print $this->horizontal_plain_menu[$menu_name];
}

} /* END OF CLASS */

?>
