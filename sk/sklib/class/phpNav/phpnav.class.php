<?php

/*************************************************************************\
 
  phpnav.class.php - a set of classes for displaying navigation items
  Copyright (C) 2001 - Paul Gareau <paul@xhawk.net>
 
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
 
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 
\*************************************************************************/

class phpNav {

	/**
	 * Object constructor
	 */

	function phpNav($link_db='') {
		if($link_db) {
			$this->setLinks($link_db);
		}
	}
	
	/**
	 * Set link array to be used
	 */

	function setLinks($link_db) {
		// if an array was passed use it
		// otherwise load the link_db file
        if(is_array($link_db)) {
            $this->_links = $link_db;
		} 
		elseif(file_exists($link_db)) {
			$line = file($link_db);
			foreach($line as $data) {
				if(ereg("^[^#\r\n]", $data)) {
					$this->_links[] = split("\t+", trim($data));
				}			
			}
		} 
		else {
			return false;
		}
		// get the number of links
		$this->_num_links = count($this->_links);
	}

	/**
	 * Create a new phpNav object
	 */

	function create($type) {
		if(!isset($this->_links)) {
			return false;
		}
        $type	= strtolower($type);

		$argc	= func_num_args();
		$args	= func_get_args();
		$props	= array();

		for($arg=1; $arg<$argc; ++$arg) {
			$props[] = $args[$arg];
		}
        switch ($type) {
			case 'trail':
				return new TrailNav($this->_links, $props[0]);
				break;
			case 'link':
				return new LinkNav($this->_links, $props[0], $props[1]);//added parent, page
				break;
			case 'tab':
				return new TabNav($this->_links, $props[0], $props[1]);
				break;
			case 'button':
				return new ButtonNav($this->_links, $props[0], $props[1]);
				break;
			case 'dropdown':
				return new DropdownNav($this->_links, $props[0]);
				break;
			case 'tree':
				return new TreeNav($this->_links, $props[0]);
				break;
        }
    }

}

/*********************************************************************/

class commonNav {

	/**
	 * Object constructor
	 */

	function commonNav() {
		// count the number of links in given array
		$this->_num_links = count($this->_links);

		// set common property values
		$this->_c_selected_is_text	= 1;
		$this->_c_bgcolor		= '#CCCCCC';
		$this->_c_tab_color		= '#DDDDDD';
		$this->_c_sel_color		= '#FFFFEE';
		$this->_c_button_color	= '#EEEEEE';
		$this->_c_link_font		= array("<font face='Helvetica,Arial,Verdana' class='phpnavfont' >", "</font>");
	}

	/**
	 * Get the name of a page, given its path
	 */

	function getPageFromPath($path='') {
		if(!$path) {
			global $PHP_SELF;
			$path = $PHP_SELF;
		}
		for($num=0; $num<$this->_num_links; $num++)
			if($this->_links[$num][0] == $path)
				return $this->_links[$num][1];
		return false;
	}

	/**
	 * Get the parent of a page
	 */

	function getPageParent($page) {
		for($num=0; $num<$this->_num_links; $num++)
			if($this->_links[$num][1] == $page)
				return $this->_links[$num][2];
		return false;
	}
}

/*********************************************************************/

class TrailNav extends commonNav {

	/**
	 * Object constructor
	 */

	function TrailNav(&$links, $page='') {
		// add args as properties
		$this->_links		= $links;
        $this->_page        = $page;

		// call parent object constructor
		$this->commonNav();

		// properties
        $this->delimiter		= ' > ';
		$this->link_font		= $this->_c_link_font;
		$this->selected_is_text	= $this->_c_selected_is_text;
    }

    function html() {
		if($this->_page_hold) $this->_page = $this->_page_hold;

        for($num=0; $num<$this->_num_links; $num++) {
			// if we found the page to act on...
			if($this->_links[$num][1]==$this->_page) {
				// if this is the current page, either print as text or a link
				if($this->selected_is_text && !$this->_page_hold) {
	                $html = $this->link_font[0].$this->_links[$num][3].$this->link_font[1]."\n";   //changed from _links[$num][1] to _links[$num][3] (LABEL)
					$html.= $this->link_font[1]."\n";
				} else {
					$html = $this->link_font[0]."<a href='".$this->_links[$num][0]."'>"
                        .$this->link_font[0].$this->_links[$num][3].$this->link_font[1]     //changed from _links[$num][1] to _links[$num][3] (LABEL)
                        ."</a>".$this->link_font[1]."\n";
				}

				// if this is not a top level link, get its parent
                if($this->_links[$num][2]!='') {
					$this->_page_hold = $this->_links[$num][2];
					$html = $this->link_font[0].$this->delimiter.$this->link_font[1]."\n".$html;
                    $html = $this->html().$html;
                } else {
                    $html= $this->link_font[0]."\n".$html;
				}
            }
        }
        return $html;
    }
}

/*********************************************************************/

class LinkNav extends commonNav {

    function LinkNav(&$links,$parent='', $page='') {
		// add args as properties
		$this->_links		= $links;
		$this->_page		= $page;
		$this->_parent		= $parent;

		// call parent object constructor
		$this->commonNav();

		// get parent of $page
        //$this->_parent = $this->getPageParent($this->_page);
        if(!$this->_parent) $this->_parent = $this->_page;


		// properties
        $this->splitter			= ' <br> '; //for a vertical list, change this to <br>
        $this->link_font		= $this->_c_link_font;
		$this->selected_is_text = $this->_c_selected_is_text;
    }

    function html() {
        global $url_par;
        $html.= $this->link_font[0]."\n";

        for($num=0; $num<$this->_num_links; $num++) {
            IF ($GLOBALS['debug']>0) echo "thispage:".$this->_links[$num][1]." page:".$this->_page." parent:".$this->_parent."<br>";
            if($this->_links[$num][1] == $this->_parent) {
                $parent_link = "<a class='linknav' href='".$this->_links[$num][0].$url_par."' title='".$this->_links[$num][3]."'>"
                               .$this->_links[$num][3]."</a>\n"; //changed from _links[$num][1] to _links[$num][3] (LABEL)
			}
            if($this->_links[$num][2]==$this->_parent) {
                if($found_links)
                    $html.= $this->splitter."\n";

				if($this->_page==$this->_links[$num][1] && $this->selected_is_text) {
					$html.= "<strong>".$this->_links[$num][3]."</strong>\n";//changed from _links[$num][1] to _links[$num][3] (LABEL)
				} else {
					$html.= "<a class='linknav' href='".$this->_links[$num][0]."' title='".$this->_links[$num][3]."'>"; //changed from _links[$num][1] to _links[$num][3] (LABEL)
		            $html.= $this->_links[$num][3];  //changed from _links[$num][1] to _links[$num][3] (LABEL)
			        $html.= "</a>\n";
				}
				$found_links = true;
            }
        }
        if(!$found_links) $html .= $parent_link;

        $html.= $this->link_font[1]."\n";
        return $html;
    }
}

/*********************************************************************/

class TabNav extends commonNav {
    function TabNav(&$links, $section='', $page='') {
		// add args as properties
		$this->_links		= $links;
		$this->_section		= $section;
        $this->_page		= $page;

		// call parent object constructor
		$this->commonNav();

		// get parent of $page
        $this->_parent = $this->getPageParent($this->_section);

        // general
        $this->lock_button_section	= '';	//buttons will always be children of this section
        $this->selected_is_text		= 1;		//the selected element will not be a link
        $this->no_kids				= 0;	//dont show buttons
        $this->show_parent			= 1;		//show the parent link with up_arrow if deep
        $this->up_arrow				= '^';			//show this next to the parent tab
        $this->table_align			= '';
        $this->table_width			= '100%';
        $this->text_padding			= '&nbsp;&nbsp;';	//pad text with this

        // tabs
        $this->tab_align			= 'left';
        $this->tab_width			= 140;
		$this->tab_shadow			= 0;
        $this->tab_space			= 2;	//space between tabs
        $this->tab_padding			= 2;	//padding of tab cells
        $this->tab_font				= $this->_c_link_font;
        $this->open_tab_font		= $this->_c_link_font;
        $this->tab_color			= $this->_c_tab_color;
        $this->open_tab_color		= $this->_c_bgcolor;

        // buttons
        $this->button_align         = 'left';
        $this->button_width         = 100;
        $this->button_space         = 0;	//space between buttons
        $this->button_padding       = 2;	//button cell padding
        $this->button_spacing       = 2;	//spacing around cells
        $this->button_font          = $this->_c_link_font;
        $this->open_button_font     = $this->_c_link_font;
        $this->button_color			= $this->_c_button_color;
        $this->open_button_color	= $this->_c_sel_color;//'6699ff';
    }


    function html() {

        $html.= "<table border=0 cellpadding=0 cellspacing=0";

        if($this->table_align)
            $html.= " align='$this->table_align'";
        $html.= " width='$this->table_width'><tr><td>\n";
        $html.= "<table border=0 cellpadding=0 cellspacing=0 align='$this->tab_align'";

        if($this->tab_width=='-')
            $html.= " width='100%'";
        $html.= "><tr>\n";

        for($num=0; $num<$this->_num_links; $num++) {
            //display a tab if we found a child page, or if it is a sublevel and we want to show the parent level tab
            if($this->_links[$num][2]==$this->_parent || ($this->_links[$num][1]==$this->_parent && $this->show_parent)) {
                //use appropriate fonts and colors
                if ($this->_links[$num][1]==$this->_section) {
                    $bgcolor = $this->open_tab_color;
                    $font = $this->open_tab_font[0];
                    $cfont = $this->open_tab_font[1];
                } else {
                    $bgcolor = $this->tab_color;
                    $font = $this->tab_font[0];
                    $cfont = $this->tab_font[1];
                }
                //only add a spacer tab if this is not the first tab
                if($found_links)
                    $html.= "<td width=$this->tab_space></td>\n";
                $html.= "<td>";
                $html.= "<table border=0 cellpadding=$this->tab_padding cellspacing=0><tr>";
                $html.= "<td bgcolor='$bgcolor' align='center'";

                //if tab width is set and not a '-', set the width
                if($this->tab_width && $this->tab_width!='-')
                    $html.= " width='$this->tab_width'";
                $html.= ">";
                $html.= "";
                $html.= $font;

                if($this->_links[$num][1]==$this->_parent && $this->show_parent)
                    $html.= $this->text_padding.$this->up_arrow;
                $html.= $this->text_padding;

                $name = ereg_replace('[[:space:]]', '&nbsp;', $this->_links[$num][3]);
                if($this->selected_is_text && $this->_links[$num][1] == $this->_section) {
                    $html.= $font.$name.$cfont;
                } else {
                    $html.= "<a href='".$this->_links[$num][0]."' title='$name'>";
                    $html.= $font.$this->_links[$num][3].$cfont;   //changed from _links[$num][1] to _links[$num][3] (LABEL)
                    $html.='</a>';
                }
                $html.= $this->text_padding.$cfont."";
                $html.= "</td>";
                $html.= "</tr></table>";
                $html.= "</td>\n";
				if($tab_shadow) {
					$html.= "<td width=$tab_shadow bgcolor='BBBBBB'></td>";
				}
                $found_links = true;
            }
        }
        $html.= "</tr></table>\n";
        $html.= "</td></tr><tr><td>\n";
        $html.= "<table border=0 cellpadding=0 cellspacing=0 width='100%' bgcolor='$this->open_tab_color'>\n";

        $use_section = ($this->lock_button_section) ? $this->lock_button_section : $this->_section;

		if($this->_section!='' && !$this->no_kids) {
			$html.= "<tr><td>";
			$html.= "<table border=0 cellpadding=$this->button_padding cellspacing=$this->button_spacing align='$this->button_align'";
			if($this->button_width=='-')
				$html.= " width='100%'";
			$html.= "><tr>\n";
            for($num=0; $num<$this->_num_links; $num++) {
                if($this->_links[$num][2]==$use_section) {
                    //set appropriate colors and fonts
                    if ($this->_links[$num][1] == $this->_page) {
                        $bgcolor = $this->open_button_color;
                        $font = $this->open_button_font[0];
                        $cfont = $this->open_button_font[1];
                    } else {
                        $bgcolor = $this->button_color;
                        $font = $this->button_font[0];
                        $cfont = $this->button_font[1];
                    }
                    $html.= "<td bgcolor='$bgcolor' align='center'";

                    if($this->button_width && $this->button_width!='-')
                        $html.= " width='$this->button_width'";
                    $html.= ">";
                    $html.= $font.$this->text_padding;

                    $name = str_replace(' ', '&nbsp;', $this->_links[$num][3]);    //changed from _links[$num][1] to _links[$num][3] (LABEL)
                    if($this->selected_is_text && $this->_links[$num][1] == $this->_page) {
                        $html.= $font.$name.$cfont;
                    } else {
                        $html.= "<a href='".$this->_links[$num][0]."' title='$name'>";
                        $html.= $font.$name.$cfont;
                        $html.= '</a>';
                    }
                    $html.= $this->text_padding.$cfont."</td>\n";

                    if($this->button_space)
                        $html.= "<td width=$this->button_space></td>\n";
                    $children = true;
                }
            }
			$html.= "</tr></table>\n";
        }

		if(! $children)
			$html.= "<tr><td height=3></td></tr>\n";

        $html.= "</td></tr></table>\n";
        $html.= "</td></tr></table>\n";

        return $html;
    }
}

/*********************************************************************/

class ButtonNav extends commonNav {
    function ButtonNav(&$links, $section='', $page='') {
		// add args as properties
		$this->_links		= $links;
		$this->_section		= $section;
        $this->_page		= $page;

		// call parent object constructor
		$this->commonNav();

        // general
        $this->selected_is_text		= 1;
        $this->style				= 'vert'; //can be horiz or vert
        $this->text_padding			= '&nbsp;&nbsp;';
        $this->table_bgcolor		= $this->_c_bgcolor;
        $this->table_width			= '';
        $this->table_align			= '';
        $this->text_align			= 'left';

        // buttons
        $this->button_align			= 'left';
        $this->button_width			= 150;
        $this->button_space			= 0;
        $this->button_padding		= 2;
        $this->button_spacing		= 2;
        $this->button_font			= $this->_c_link_font;
        $this->open_button_font		= $this->_c_link_font;
        $this->button_color			= $this->_c_button_color;
        $this->open_button_color	= $this->_c_sel_color;
    }

    function html() {

        $html.= "<table border=0 cellpadding=0 cellspacing=0 width='$this->table_width'";

        if($this->table_align)
            $html.= " align='$this->table_align'";
        $html.= ">";
        $html.= "<tr><td bgcolor='$this->table_bgcolor'>\n";
        $html.= "<table border=0 cellpadding=$this->button_padding cellspacing=$this->button_spacing align='$this->button_align'";

        if($this->button_width=='-')
            $html.= " width='100%'";
        $html.= "><tr>\n";
        for($num=0; $num<$this->_num_links; $num++) {
            if($this->_links[$num][2]==$this->_section) {
                if($this->_links[$num][1]==$this->_page) {
                    $bgcolor = $this->open_button_color;
                    $font = $this->open_button_font[0];
                    $cfont = $this->open_button_font[1];
                } else {
                    $bgcolor = $this->button_color;
                    $font = $this->button_font[0];
                    $cfont = $this->button_font[1];
                }
                if($this->style=='vert' && $found_links)
                    $html.='<tr>';
                $html.= "<td bgcolor='$bgcolor' align='$this->text_align'";

                if($this->button_width && $this->button_width!='-')
                    $html.= " width='$this->button_width'";
                $html.= ">";
                $html.= $font.$this->text_padding;
                $name = ereg_replace('[[:space:]]', '&nbsp;', $this->_links[$num][1]);

                if($this->selected_is_text && $this->_links[$num][1] == $this->_page) {
                    $html.= $font.$name.$cfont;
                } else {
                    $html.= "<a href='".$this->_links[$num][0]."' title='".$this->_links[$num][1]."'>";
                    $html.= $font.$name.$cfont;
                    $html.= '</a>';
                }
                $html.= $this->text_padding.$cfont."</td>\n";

                if($this->style=='vert')
                    $html.='</tr>';
                if($this->button_space)
                    $html.= "<td width=$this->button_space></td>\n";
                $found_links = true;
            }
        }
        if(! $found_links)
            $html.= "<td height=1></td>\n";
        if($this->style!='vert')
            $html.= "</tr>\n";

		$html.= "</table>\n";
        $html.= "</td></tr></table>\n";

        return $html;
    }
}

/*********************************************************************/

class DropdownNav extends commonNav {
    function DropdownNav(&$links, $level='') {
		// add args as properties
		$this->_links	= $links;
		$this->_level	= $level;

		// call parent object constructor
		$this->commonNav();

		$this->size		= 1;
        $this->text		= 'Quick Nav';
        $this->name		= 'quick_nav';
        $this->indent	= '-&nbsp;';
        $this->font		= $this->_c_link_font;
    }

    function html() {
        global $PHP_SELF;

        $this->_num_links = count($this->_links);

        $html.= "<form name='$this->name' action='$PHP_SELF' method='post'>\n";
        $html.= "<table border=0 cellpadding=0 cellspacing=0><tr>\n";
        $html.= "<td>\n";
        $html.= $this->font[0]."\n";
        $html.= "<select name='$this->name' size=$this->size";
        $html.= " onChange=\"if(this.value != '') document.location = this.value\">\n";
        $html.= "<option value='' selected>$this->text\n";
        $html.= "<option value=''>---------\n";

        for($num=0; $num<$this->_num_links; $num++) {
            if($this->_links[$num][1]==$this->_level || ($this->_level=='' && $this->_links[$num][2]==$this->_level)) {
                $html.= "<option value='".$this->_links[$num][0]."'>".$this->_links[$num][1]."\n";

                for($sub_num=0; $sub_num<$this->_num_links; $sub_num++) {
                    if($this->_links[$sub_num][2]==$this->_links[$num][1])
                        $html.= "<option value='".$this->_links[$sub_num][0]."'>$this->indent".$this->_links[$sub_num][1]."\n";
                }
            }
        }
        $html.= "</select>\n";
        $html.= $this->font[1]."\n";
        $html.= "</td>\n";
        $html.= "</tr></table>\n";
        $html.= "</form>\n";

        return $html;
    }
}

/*********************************************************************/

class TreeNav extends commonNav {
    function TreeNav(&$links, $level='') {
		// add args as properties
		$this->_links	= $links;
		$this->_level	= $level;

		// call parent object constructor
		$this->commonNav();

        $this->max_depth	= 2;
        $this->link_font    = $this->_c_link_font;
    }

    function html($level='') {
        if($this->depth > $this->max_depth) return false;
        if(! $level) $level = $this->_level;

        for($num=0; $num<$this->_num_links; $num++) {
            if($this->_links[$num][2] == $level) {
                $html.= "<li><a href='".$this->_links[$num][0]."'>"
                        .$this->link_font[0].$this->_links[$num][3].$this->link_font[1]."</a>";//changed from _links[$num][1] to _links[$num][3] (LABEL)
                $this->depth++;
                $html.= $this->html($this->_links[$num][1]);
                $this->depth--;
            }
        }
        if($html) $html = "<ul>\n$html</ul>\n";
        return $html;
    }
}

?>
