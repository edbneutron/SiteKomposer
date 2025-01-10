<?php
/*
** treemenu.php class version 1.0 12/24/99 Sword_Su@263.net
**                    version 1.2 04/15/01 raymond.lalevee@libertysurf.fr
**                    version 1.3 04/22/01 sarah@propertyinvestor.co.nz
**                    class submenu changed by Edgar Bueltemeyer to return html instead of echo
**                    and mid= in the link instead of id=
*/

/*******************************************************
 * This is based on v1.0. Thanks for original code.
 * v1.2 adds the ability to have a more customised look of each
 * cell. I'd probably use styles to control the standard look and
 * just pass the exceptions. Worth considering incorporating the here.
 *
 * My Changes:
 * 1. Handles frames by using javascript. Change the frame names in the
 *    two javascript functions to your own frames and change the urls to
 *    some of your own and it should start working.
 * 2. Handles menus's without submenus. We have significant pages which needed
 *    their own bar but didn't have any other options.
 * 3. Handles links to external and internal pages
 * 4. Added constructors to simply code
 * 5. Controls appearance through styles
 * 6. Makes selected items upper case, other effects may be applied. 
 *    SubId tracks the submenu selected so that it can be highlighted. 
 *    I've just used uppercase to highlight.
 * 7. Dropped the $action parameter. It wasn't being used.
 * 8. Added AutoClose to the menu parameters, allows you to keep the page
 *    open to add other functionality
 * 9. Added a concept which started life being called fragments but which
 *    really just calls a separate function to allow something quite different
 *    to be used. In this example a cut down list of the magazine issues we
 *    have produced. The function returns the html. Have tried to limit the 
 *    use of echo to a few strategic places.
 *
 * FINALLY as a php newbie it's been a really interesting experience getting
 * this code and playing with it.
 ********************************************************/
class submenu { 

  var $urls;    // url 
  var $desps;   // description 
  var $dests;   // destinations
  var $mids;    // Komposer sk_nav_tree ID
  var $cot = 0; // count 
  var $id;      // id of the new menu, ie submenu 3 

  function submenu($id = 1,$firstlgfx=0) { // Constructor. Creates the new submenu
     $this->id = $id;
     $this->firstlgfx=$firstlgfx;
  }// constructor

  function add($url, $desp, $dest="self",$mid, $fragments="") { 
    $this->urls[$this->cot]=$url;
    $this->desps[$this->cot]=$desp; 
    $this->dests[$this->cot]=$dest;
    $this->mids[$this->cot]=$mid;
    $this->fragments[$this->cot]=$fragments;
    $this->cot++;
  } // add
  
  function open($subid = -1) { // selected menu option
    $i=0;
    $output="";
    while($i <= $this->cot) {
      if ($i==0) { // Menu Heading
         $newUrl = $this->giveUrl();
          $output.=$this->buildURL( $i, strtoupper ($this->desps[0]), $newUrl, $this->dests[$i],$this->fragments[$i],'th',$this->firstlgfx);
         }
       else { // submenus
          if ( $subid == $this->mids[$i]) { $this->desps[$i] = "<SPAN class=\"treemenusel\">".$this->desps[$i]."</SPAN>";}
          $output.=$this->buildURL($i, $this->desps[$i], $this->urls[$i], $this->dests[$i],$this->fragments[$i]);
        }
      $i++;
    } // while ($i<=$this->cot)
    return $output;
  } // open
  
  function closed() { 
  /* don't show the submenu, just the banner   */
    $newUrl = $this->giveUrl();
    $output = $this->buildURL(0, $this->desps[0], $newUrl, $this->dests[0],$this->fragments[0],'th',$this->firstlgfx);
    return $output;
  } // closed

  function giveUrl() {
    if ($this->urls[0] == ""){$retUrl = $PHP_SELF."?mid=" . $this->id;}// . "&subid=0";} changed by edb
    else {$retUrl = $this->urls[0] ;}
     return $retUrl;
  }

  function buildURL($subid, $desp, $url="", $dest="", $fragment="", $celltype="td align=\"center\"", $graphic=0) {
  global $docrelpath;
     // Works for all options?, receives the parameters and goes from there.
     $output = "<tr><" . $celltype . ">";
     if ($url == "") {
       if ($fragment == "") {$output .= $desp;}
        else {$output .= $fragment($subid);} // always pass the subid just in case we want it. 
     }
     else {
      // got to be sneaky with the quote marks here to acheive my goal.
      if($graphic==1){
       $output .= "<a href='";
       if ($dest == "frame") { $output .= 'javascript:dolink("' . $this->id .'","' . $subid .'","' . $url . '")' . "'";} // $dest = frame
        elseif ($dest == "self"){$output .= $url . "' target='_self'";}
        else {$output .= 'javascript:doextlink("' . $this->id . '","' . $subid . '","' . $url . '"' . ")'";}
        $output .=' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage(\'navpic'.$this->id.'\',\'\',\''.$docrelpath.'sk/res/nav/'.$this->id.'_over.gif\',1)">';
        $output .='<img src="'.$docrelpath.'sk/res/nav/'.$this->id.'.gif" alt="" name="navpic'.$this->id.'" border="0"></a>';
      
      }else{
       $output .= "<a href='";
       if ($dest == "frame") { $output .= 'javascript:dolink("' . $this->id .'","' . $subid .'","' . $url . '")' . "'>";} // $dest = frame
        elseif ($dest == "self"){$output .= $url . "' target='_self'>";}
        else {$output .= 'javascript:doextlink("' . $this->id . '","' . $subid . '","' . $url . '"' . ")'>";}
        $output .= $desp . "</a>";
      }
     } // url=""
     $output .= '</' . $celltype . '></tr>' . "\n";
     return $output;
  } // buildURL
} // submenu class

class menu { /* The main class */

  var $submenus; // array of submenu information 
  var $cot = 0;  // count 
  var $id;       // id column? 
  var $subid;    // pointer to the submenu 
  var $autoclose; // add the </body></html> tags to the code?

  function menu ($id = 2, $subid = -1, $autoclose = "Auto") { // constructor
    $this->id = $id;
     $this->subid = $subid;
     $this->autoclose = $autoclose;
  }  // constructor
    
  function add($submenu) { /* link the submenu to the parent */
    $this->submenus[$this->cot]=new submenu;
     $this->submenus[$this->cot]=$submenu;
     $this->cot++;
  } // add

  function getHeader() {
  /* Everything you want to go above the menu. This would probably be best as an included
     html fragment, put in here to allow an example. Few more styles than needed for this example. Remove as necessary.
  */
  ?>
  <html>
  <head>
  <meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
        <title>Residential Property Investor magazine - Menu </title>
        <style>
        body  { text-align: color: blue; font-size: 9pt; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; }
        b  { text-align: color: blue; font-size: 9pt; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; font-weight: bold; }
        input  { text-align: color: blue; font-size: 9pt; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; }
        textarea { text-align: color: blue; font-size: 9pt; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; }
        td  { text-align: center; color: blue; font-size: 9pt; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; }
        th  { text-align: center; color: blue; font-size: 10pt; font-family: Arial, Helvetica, Geneva, Swiss, SunSans-Regular; font-weight: bold; background-color: #ffd700; }
        </style>
        <script language="JavaScript">
        function dolink(id, subid, newurl) {
          parent.MAINSTUFF.location = newurl;
          parent.TOC_WINDOW.location = "?id=" + id + "&subid=" + subid;
      }
        function doextlink(id, subid, newurl) {
        window.open(newurl,"win");
          parent.TOC_WINDOW.location = "?id=" + id + "&subid=" + subid;
      }
      function gomthurl() { 
        var urlstring = document.frmBackMth.mthYear.value;
        parent.MAINSTUFF.location = "./issues/issue.php?issuename=" + urlstring;
        parent.TOC_WINDOW.location = "?id=2&subid=3";
         }
        </script>
        </head>
    <body bgcolor="#ffffff" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"> 
        <?

  } // getHeader
  
  function show() { /* Public Function */
    $i=0;
     $this->getHeader();
     $tmp = new submenu;
     echo '<table width="100%" border="0">';
     while ($i<$this->cot) {
        $tmp=$this->submenus[$i];
       if ($tmp->id == (string)$this->id) { $tmp->open($this->subid); }
       else { $tmp->closed(); }
       $i++;
     } // while
    echo '</table>';    
     if ($this->autoclose == "Auto"){echo "</body></html>";}
  }// show
   
} // End of Class definition

function issuelist($subid=0) { // separate from the classes
  //$title = "Back Issues";
  //if ($subid == 3) {$title = strtoupper($title);}
  $output = '<form name="frmBackMth">Back Issues<br><select name="mthYear" onchange="gomthurl();">';
  $output .= '<OPTION VALUE="jul00">July 00</OPTION><OPTION VALUE="aug00">Aug 00</OPTION>';
  $output .= '</select><input type="button" name="goMthURL" value="Go" onclick="gomthurl()"></form>';
  return $output;
}


?>