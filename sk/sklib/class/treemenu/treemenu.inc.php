<?php
//version sk 1.1

class TreeMenu {

/************************************************************

  This treemenu class is based on Bjorge Dijkstra's 
  PHP TreeMenu 1.1. I have wrapped his code up and
  made loading from various sources easier. 

  Bjorge Dijkstra's Original Script:
  -----------------------------------
  http://www.zinc.f2s.com/scriptorium/index.php/treemenu
  
  You can find my adaptation here:
  --------------------------------
  http://www.bitbuddha.com/php/treemenu/treemenu.inc
  http://www.bitbuddha.com/php/treemenu/demo.php

  Example:
  To load the demomenu.txt file supplied with Bjorge's
  distribution, do the following:

  <?php
  $tree = new TreeMenu("a", "demomenu.txt");
  $tree->show();
  ? >

  A global variable $e was used to pass expanded nodes
  from one invocation of the script to the next in Bjorge's
  version. Now you must specify the variable name when
  you create your TreeMenu object:

  $tree = new TreeMenu("a");

  When the object is created, this variable name gets
  the letters "tmv" prepended to it. This allows the
  constructor to loop through the global variables
  and pick out those that it needs when writing certain URLs.

  This came about as a modification to some code sent
  to me by Enrico Stahn [enrico.stahn@gmx.net] on 11/29/2001
  that added multimenu functionality to the script. Much
  thanks to him. I have archived his version at the following URL:

  http://www.bitbuddha.com/php/treemenu/stahn.112901.treemenu.inc  

  I got rid of the overloaded constructor that would accept
  a file name or an array and act accordingly. This required
  programmers to know too much about the implementation
  of the class. I left the ability to pass a file name and supplied
  an addNode() method. You can use it like this:

  <?php
  include "treemenu.inc";
  $tree = new treemenu("a");
  $tree->addNode(1, "grandpa"); 
  $tree->addNode(2, "pa"); 
  $tree->addNode(3, "son"); 
  $tree->addNode(2, "uncle"); 
  $tree->show();
  ? >

  Look at the addNode method signature to see the two optional 
  parameters (ok, so they are $link and $target).

  Your tree will look like this:

  + grandpa
  |
  |----+ pa  
  |    |
  |    |---o son
  |
  |---o uncle

  If you have some hierarchical data in a database
  then it would be pretty easy to write a recursive
  function to build your tree dynamically using the
  addNode() method.

  The loadTreeFromFile() method allows you to add
  as many node files to a tree as you want. You can
  mix it up with other sources (database, etc) using
  the addNode() method, too. An example:

  <?php
  include "treemenu.inc";
  $tree = new TreeMenu("a");
  $tree->addNode(1, "grandpa"); 
  $tree->addNode(2, "pa"); 
  $tree->addNode(3, "son"); 
  $tree->addNode(2, "uncle");
  $tree->loadTreeFromFile("demomenu.txt");
  $tree->show();
  ? >

  There is an additional getHTMLTable() method if
  you ever need that for some reason. It returns
  the table instead of echoing it.

  Denny Shimkoski 2001    dhscompguy@yahoo.com
  
  Adapted to site-Komposer by
  Edgar Bültemeyer 2002

************************************************************/


    var $tree        = array();
    var $expand        = array();
    var $visible        = array();
    var $levels        = array();
    var $explevels        = array();
    var $urlparams        = array();
    var $branch_ary        = array();
    var $maxlevel        = 0;
    var $i            = 0;
    var $urlparam;
    var $script;
    var $admin    = 0; // shows all entries

    var $img_expand        = "tree_expand.gif";
    var $img_collapse    = "tree_collapse.gif";
    var $img_line        = "tree_vertline.gif";  
    var $img_split        = "tree_split.gif";
    var $img_end        = "tree_end.gif";
    var $img_leaf        = "tree_leaf.gif";
    var $img_spc        = "tree_space.gif";
    var $img_hspc        = "tree_hspace.gif";
    var $img_add        = "tree_add.gif";
    var $img_add1        = "tree_add1.gif";
    var $img_path        = "";
    function treemenu($urlparam, $nodefile = "",$img_path="",$admin=0){
        $this->script = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        $this->urlparam = "tmv".$urlparam;
	if(!isset($GLOBALS[$this->urlparam])) $GLOBALS[$this->urlparam]="";
        $this->img_path = $img_path;
        $this->admin=$admin;
        reset($GLOBALS["HTTP_GET_VARS"]);
        while (list($key,) = each ($GLOBALS["HTTP_GET_VARS"])){
                  //if (substr($key, 0, 3) == "tmv"){    //changed to include all GET_VARS
                $this->urlparams[] = $key;
            //}
        }

        if (!empty($nodefile)) $this->loadTreeFromFile($nodefile);
    }

    function addNode($level, $text, $link = "", $target = "", $id = 1, $sort_nr = 1,$parent = 0){
        $this->tree[$this->i][0] = $level;
        $this->tree[$this->i][1] = $text;
        $this->tree[$this->i][2] = $link;
        $this->tree[$this->i][3] = $target;
        $this->tree[$this->i][4] = 0;
        $this->tree[$this->i][5] = $id;  // Page - ID
        $this->tree[$this->i][6] = $sort_nr;  // sort_nr
        $this->tree[$this->i][7] = $parent;  // parent
        if ($this->tree[$this->i][0] > $this->maxlevel) $this->maxlevel = $this->tree[$this->i][0];

        $this->expand[$this->i]=0;
        $this->visible[$this->i]=0;
        $this->levels[$this->i]=0;
        $this->i++;
    }

    function loadTreeFromFile($nodefile){
        $fd = fopen($nodefile, "r");
        if ($fd == 0) die($this->script." : Unable to open file ".$nodefile);
        while ($buffer = fgets($fd, 4096)){
            $level = strspn($buffer,".");
            $node = explode("|", rtrim(substr($buffer,$level)));
            $text = $node[0];
            $link = $node[1];
            $target = $node[2];
            $this->addNode($level, $text, $link, $target);
        }
        fclose($fd);
    }

    function setExpandedNodesFromURL(){
        if (!empty($GLOBALS[$this->urlparam])) $this->explevels = explode("|",$GLOBALS[$this->urlparam]);
        $this->setExpandedNodes();
    }
    
    function setExpandedNodesFromID($page_id){
        
        for($num=0; $num<$this->i; $num++)
            //if ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'].=$num."-".$this->tree[$num][5]."-".$page_id." .. ";
            if($this->tree[$num][5] == $page_id){
            
            $this->explevels[]=$num;
            if($this->tree[$num][7]<>$this->tree[$num][5])$this->setExpandedNodesFromID($this->tree[$num][7]);
            }
        
    }

    function setExpandedNodes(){
        $i=0;
        while($i<count($this->explevels)){
            $this->expand[$this->explevels[$i]]=1;
            $i++;
        }
    }

    function setEndNodes(){
        $lastlevel=$this->maxlevel;
        for ($i=count($this->tree)-1; $i>=0; $i--){
            if ( $this->tree[$i][0] < $lastlevel ){
                for ($j=$this->tree[$i][0]+1; $j <= $this->maxlevel; $j++){
                    $this->levels[$j]=0;
                }
            }

            if ( $this->levels[$this->tree[$i][0]]==0 ){
                $this->levels[$this->tree[$i][0]]=1;
                $this->tree[$i][4]=1;
            } else {
                $this->tree[$i][4]=0;
            }
            $lastlevel=$this->tree[$i][0];
        }
    }

    function setVisibleNodes(){
        // all root nodes are always visible
        for ($i=0; $i < count($this->tree); $i++){
            if ($this->tree[$i][0]==1){
                $this->visible[$i]=1;
            }
        }

        for ($i=0; $i < count($this->explevels); $i++){
            $n = $this->explevels[$i];
            if ( ($this->visible[$n]==1) && ($this->expand[$n]==1) ){
                $j=$n+1;
                while ( $this->tree[$j][0] > $this->tree[$n][0] ){
                    if ($this->tree[$j][0]==$this->tree[$n][0]+1) $this->visible[$j]=1;
                    $j++;
                }
            }
        }
    }

    function show(){
        echo $this->getHTMLTable();
    }


      function getHTMLTable($mindepth=0,$page_id=0,$parent_id=0,$sel_branch=0){

        $this->setExpandedNodesFromURL();
        $this->setEndNodes();
        $this->setVisibleNodes();
        DEBUG_out(2,"debug2","getHTMLTable() treemenu: mindepth:".$mindepth." explevels:".print_array($this->explevels).print_array($this->visible)."<br>this->script=".$this->script);
        
        /*********************************************
         *  Output nicely formatted tree             *
         *********************************************/

        for ($i=0; $i<$this->maxlevel; $i++) $this->levels[$i]=1;

        $this->maxlevel++;
          $html="";
        if($this->admin==1) 
            $html.=$GLOBALS[site_name]."<a href=\"sk_nav_tree_edit.php?p=self&p_name=".$this->tree[$cnt][1]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."&alvis=".$GLOBALS['alvis']."&framed=1&action=add&current_site=".$GLOBALS['current_site']."\" target=\"editform\">".
                             "<img src=\"".$this->img_path.$this->img_add1."\" name=\"tree_add".$this->tree[$cnt][5]."\" align=\"baseline\" width=\"16\" alt=\"hinzufügen\" height=\"10\" border=\"0\" onMouseOver=\"MM_swapImage('tree_add".$this->tree[$cnt][5]."','','".$this->img_path.$this->img_add."',1)\" onMouseOut=\"MM_swapImgRestore()\">
                              </a>
                              ";
        $html .= "<table cellspacing=0 cellpadding=0 border=0>\n"; //cols=".($this->maxlevel+3)."
        $html .= "<tr>";
        for ($i=0; $i<$this->maxlevel; $i++) $html .= "<td width=10 height=1><img src=\"".$this->img_path.$this->img_spc."\" border=\"0\"><img src=\"".$this->img_path.$this->img_spc."\" border=\"0\"></td>";
        $html .= "<td width=150 height=1><img src=\"".$this->img_path.$this->img_spc."\" border=\"0\"></td></tr>\n";

        $cnt=0;

        while ($cnt<count($this->tree)){
            if ($this->visible[$cnt] && $this->tree[$cnt][0] > $mindepth){

        /****************************************
         * start new row                        *
         ****************************************/

                $html .= "<tr>";
      
        /****************************************
         * vertical lines from higher levels    *
         ****************************************/

                $i=0;
                while ($i<$this->tree[$cnt][0]-1){
                    if ($this->levels[$i]==1){
                        $html .= "<td><a name='$cnt'></a><img src=\"".$this->img_path.$this->img_line."\"></td>";
                    } else {
                        $html .= "<td><a name='$cnt'></a><img src=\"".$this->img_path.$this->img_spc."\"></td>";
                    }
                    $i++;
                }
      
        /****************************************
         * corner at end of subtree or t-split  *
         ****************************************/         

                if ($this->tree[$cnt][4]==1){
                    $html .= "<td><img src=\"".$this->img_path.$this->img_end."\"></td>";
                    $this->levels[$this->tree[$cnt][0]-1]=0;
                } else {
                    $html .= "<td><img src=\"".$this->img_path.$this->img_split."\"></td>";                  
                    $this->levels[$this->tree[$cnt][0]-1]=1;    
                }
      
        /********************************************
         * Node (with subtree) or Leaf (no subtree) *
         ********************************************/

                if ($this->tree[$cnt+1][0]>$this->tree[$cnt][0]){
        
        /****************************************
         * Create expand/collapse parameters    *
         ****************************************/

                    reset($this->urlparams);
                    while (list(,$value) = each ($this->urlparams)){
                             if (!empty($GLOBALS[$value]) && $value <> $this->urlparam) {
                                 $otherparams .= $value."=".$GLOBALS[$value]."&";
                             }
                    }
                    
                    $params="?".$otherparams.$this->urlparam."=";

                    $i=0;
                    while($i<count($this->expand)){
                        if ( ($this->expand[$i]==1) 
                            && ($cnt!=$i) 
                            || ($this->expand[$i]==0 && $cnt==$i)){

                            $params=$params.$i;
                            $params=$params."|";
                        }
                        $i++;
                    }
                    
                    $otherparams = "";

                    if ($this->expand[$cnt]==0){
                        $html .= "<td><a href=\"".$this->script.$params."#$cnt\"><img src=\"".$this->img_path.$this->img_expand."\" border=\"0\"></a></td>";
                    } else {
                        $html .= "<td><a href=\"".$this->script.$params."#$cnt\"><img src=\"".$this->img_path.$this->img_collapse."\" border=\"0\"></a></td>";
                    }

                } else {

        /*************************
         * Tree Leaf             *
         *************************/

                    $html .= "<td><img src=\"".$this->img_path.$this->img_leaf."\"></td>";         
                }
      
        /****************************************
         * output item text                     *
         ****************************************/
                if ($this->tree[$cnt][2]==""){
                    $html .= "<td colspan=".($this->maxlevel-$this->tree[$cnt][0]).">".$this->tree[$cnt][1]."</td>";
                } else {
                
                    if ($this->admin==1) {
                    $html .= "<td colspan=".($this->maxlevel-$this->tree[$cnt][0])." valign=\"absmiddle\"><nobr><a href=\"sk_nav_tree_edit.php?mid=".$this->tree[$cnt][5]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."&alvis=".$GLOBALS['alvis']."&framed=1\" class=\"tree\" target=\"editform\">".$this->tree[$cnt][6]."&nbsp;".$this->tree[$cnt][1]."</a>".
                             "<a href=\"sk_nav_tree_edit.php?p=".$this->tree[$cnt][5]."&p_name=".$this->tree[$cnt][1]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."&alvis=".$GLOBALS['alvis']."&framed=1&action=add&current_site=".$GLOBALS['current_site']."\"  class=\"tree\" target=\"editform\">".
                             "<img src=\"".$this->img_path.$this->img_add1."\" name=\"tree_add".$this->tree[$cnt][5]."\" align=\"baseline\" alt=\"hinzufügen\" width=\"16\" height=\"10\" border=\"0\" onMouseOver=\"MM_swapImage('tree_add".$this->tree[$cnt][5]."','','".$this->img_path.$this->img_add."',1)\" onMouseOut=\"MM_swapImgRestore()\">
                              </a></nobr></td>";
                    }else{
                    $html .= "<td colspan=".($this->maxlevel-$this->tree[$cnt][0])."><nobr><a href=\"".$this->tree[$cnt][2]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."\" class=\"tree\" target=\"".$this->tree[$cnt][3]."\" class=\"tree\">";
                    if($this->tree[$cnt][5]==$page_id){ $html.="<strong>".$this->tree[$cnt][1]."<strong>";}
                    else {$html.=$this->tree[$cnt][1];}
                    $html.="</a></nobr></td>";
                    }
                }

        /****************************************
         * end row                              *
         ****************************************/
              
                $html .= "</tr>\n";
            }
            //if ($this->tree[$cnt][0] == $parentdepth && $this->tree[$cnt][5] != $parent) break;
            $cnt++;
        }

        $html .= "</table>\n";
        return $html;
    }
    
        /*********************************************
         *  Output nicely formatted tree             *
         *  Without expand/collapse icons            *
         *  optional just subtree                    *
         *********************************************/
    function getHTMLTable_exto($page_id=0,$mindepth=0,$onlybranch=0,$parent_links=1,$current_page_id=0,$view_all=0,$sel_branch=0){
        
        if($onlybranch==1){
         $BranchParent=$this->getBranchParent($page_id);
         if($parent_id<>0) if($BranchParent!=$parent_id) return;
         
	}else{$BranchParent=0;}
        if($current_page_id==0) $current_page_id=$page_id;
        if ($view_all==1) $sitemap="sitemap"; 
        $this->setExpandedNodesfromID($page_id);
        $this->explevels=array_reverse($this->explevels);
        $this->setExpandedNodes();
        $this->setEndNodes();
        $this->setVisibleNodes();
        DEBUG_out(2,"debug2","getHTMLTable_exto() format(1) page:".$page_id."  mindepth:".$mindepth." Parent:".$BranchParent);
        DEBUG_out(3,"debug3","explevels:".print_array($this->explevels)."view_all:".$view_all." maxlevel:".print_array($this->maxlevel));
        DEBUG_out(4,"debug4","tree:".print_array($this->tree));


        /*********************************************
         *  Output nicely formatted tree             *
         *********************************************/

        for ($i=0; $i<$this->maxlevel; $i++) $this->levels[$i]=1;

        $this->maxlevel++;
          $html="";
      if($this->admin==1) 
            $html.=$GLOBALS['site_name']."<a href=\"sk_nav_tree_edit.php?p=self&".$this->urlparam."=".$GLOBALS[$this->urlparam]."&alvis=".$GLOBALS['alvis']."&framed=1&action=add&current_site=".$GLOBALS['current_site']."\" target=\"editform\">".
                             "<img src=\"".$this->img_path.$this->img_add1."\" name=\"tree_add\" align=\"baseline\" width=\"16\" alt=\"hinzufügen\" height=\"10\" border=\"0\" onMouseOver=\"MM_swapImage('tree_add','','".$this->img_path.$this->img_add."',1)\" onMouseOut=\"MM_swapImgRestore()\">
                              </a>
                              ";
        
        $html .= "<table cellspacing=0 cellpadding=0 border=0>\n"; //cols=".($this->maxlevel-($mindepth-1))."
        $html .= "<tr>";
        for ($i=$mindepth; $i<$this->maxlevel; $i++) $html .= "<td width=1 height=1><img src=\"".$this->img_path.$this->img_hspc."\" border=\"0\" height=\"1\"></td>";
        $html .= "<td height=1 width=\"100\"><img src=\"".$this->img_path.$this->img_spc."\" border=\"0\"></td></tr>\n";

        $cnt=0;
        while ($cnt<count($this->tree)){
      
          if (($this->visible[$cnt] && $this->tree[$cnt][0] > $mindepth) || $view_all==1){
                
                
        /****************************************
         * start new row                        *
         ****************************************/

                $html .= "<tr>";
      
        /****************************************
         * vertical lines from higher levels    *
         ****************************************/
               $i=$mindepth;
                while ($i<$this->tree[$cnt][0]-1){
                
                    if ($this->levels[$i]==1 && $i>$mindepth){
                        $html .= "<td><a name='$cnt'></a><img src=\"".$this->img_path.$this->img_line."\"></td>";
                    } else {
                        $html .= "<td><a name='$cnt'></a><img src=\"".$this->img_path.$this->img_spc."\"></td>";
                    }
                    $i++;
                }
        /****************************************
         * corner at end of subtree or t-split  *
         ****************************************/         

                if ($this->tree[$cnt][4]==1 && $this->tree[$cnt][0]>$mindepth+1){
                    $html .= "<td><img src=\"".$this->img_path.$this->img_end."\"></td>";
                    $this->levels[$this->tree[$cnt][0]-1]=0;
                } elseif($this->tree[$cnt][0]>$mindepth+1) {
                    $html .= "<td><img src=\"".$this->img_path.$this->img_split."\"></td>";                  
                    $this->levels[$this->tree[$cnt][0]-1]=1;    
                }
    
        /********************************************
         * Node (with subtree) or Leaf (no subtree) *
         ********************************************/

                if ($this->tree[$cnt+1][0]>$this->tree[$cnt][0] && $this->tree[$cnt][0]>$mindepth+1){
        
        /****************************************
         * Create expand/collapse parameters    *
         ****************************************/

                    reset($this->urlparams);
                    while (list(,$value) = each ($this->urlparams)){
                             if (!empty($GLOBALS[$value]) && $value <> $this->urlparam) {
                                 $otherparams .= $value."=".$GLOBALS[$value]."&";
                             }
                    }
                    
                    $params="?".$otherparams.$this->urlparam."=";

                    $i=0;
                    while($i<count($this->expand)){
                        if ( ($this->expand[$i]==1) 
                            && ($cnt!=$i) 
                            || ($this->expand[$i]==0 && $cnt==$i)){

                            $params=$params.$i;
                            $params=$params."|";
                        }
                        $i++;
                    }
                    
                    $otherparams = "";
                    $html .= "<td><a href=\"".$this->script.$params."#$cnt\"><img src=\"".$this->img_path.$this->img_leaf."\" border=\"0\"></a></td>";
                    
                    

                } elseif ($this->tree[$cnt][0]>($mindepth+1)) {

        /*************************
         * Tree Leaf             *
         *************************/
                $html .= "<td><img src=\"".$this->img_path.$this->img_leaf."\"></td>";  
                    
                }else{
                
                $html .= "<td><img src=\"".$this->img_path.$this->img_spc."\"></td>"; 
                
                }
      
        /****************************************
         * output item text                     *
         ****************************************/
                $colspan=(($this->maxlevel+1)-$this->tree[$cnt][0]);
                

                if ($this->tree[$cnt][2]==""){
                    $html .= "<td colspan=".$colspan.">".$this->tree[$cnt][1]."</td>";
                } else {
                
                    if ($this->admin==1) {
            $colspan=($this->maxlevel-$this->tree[$cnt][0]);    
            if ($this->tree[$cnt][0]==1) $colspan=$this->maxlevel+1;
            
                    $html .= "<td colspan=".($colspan)." valign=\"absmiddle\"><nobr><a href=\"sk_nav_tree_edit.php?mid=".$this->tree[$cnt][5]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."&alvis=".$GLOBALS['alvis']."&framed=1\" class=\"atree\" target=\"editform\"><span class=\"tree\">".$this->tree[$cnt][6]."&nbsp;".$this->tree[$cnt][1]."</span></a>".
                             "<a href=\"sk_nav_tree_edit.php?p=".$this->tree[$cnt][5]."&p_name=".$this->tree[$cnt][1]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."&alvis=".$GLOBALS['alvis']."&framed=1&action=add&current_site=".$GLOBALS['current_site']."\" class=\"tree\" target=\"editform\">".
                             "<img src=\"".$this->img_path.$this->img_add1."\" name=\"tree_add".$this->tree[$cnt][5]."\" align=\"baseline\" alt=\"hinzufügen\" width=\"16\" height=\"10\" border=\"0\" onMouseOver=\"MM_swapImage('tree_add".$this->tree[$cnt][5]."','','".$this->img_path.$this->img_add."',1)\" onMouseOut=\"MM_swapImgRestore()\">
                              </a></nobr></td>";
                    }else{
                    DEBUG_out(3,"debug3","tree[5]:".$this->tree[$cnt][5]."tree[7]:".$this->tree[$cnt][7]." parent_links:".$parent_links);
                    $html .= "<td colspan=".($colspan)."><nobr>";
                    if($this->tree[$cnt][5]==$current_page_id && $parent_links==9){ $html.="<div class=\"treesel$sitemap\"><strong>".$this->tree[$cnt][1]."</strong></div>";}
                    elseif($this->tree[$cnt][5]==$current_page_id && $this->tree[$cnt][5]==$this->tree[$cnt][7] && $parent_links==1){$html.="<a href=\"".$this->tree[$cnt][2]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."\" class=\"treeparentsel$sitemap\" target=\"".$this->tree[$cnt][3]."\">".$this->tree[$cnt][1]."</a>";}
                    elseif($sel_branch==1 && in_array($this->tree[$cnt][5],$this->branch_ary) && $this->tree[$cnt][5]==$this->tree[$cnt][7]) {$html.="<a href=\"".$this->tree[$cnt][2]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."\" class=\"treeparentsel$sitemap\" target=\"".$this->tree[$cnt][3]."\">".$this->tree[$cnt][1]."</a>";}
                    elseif($sel_branch==1 && in_array($this->tree[$cnt][5],$this->branch_ary)) {$html.="<a href=\"".$this->tree[$cnt][2]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."\" class=\"treesel$sitemap\" target=\"".$this->tree[$cnt][3]."\">".$this->tree[$cnt][1]."</a>";}
                    elseif($this->tree[$cnt][5]==$this->tree[$cnt][7] && $parent_links==1){$html.="<a href=\"".$this->tree[$cnt][2]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."\" class=\"treeparent$sitemap\" target=\"".$this->tree[$cnt][3]."\">".$this->tree[$cnt][1]."</a>";}
                    elseif($this->tree[$cnt][5]==$current_page_id) {$html.="<a href=\"".$this->tree[$cnt][2]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."\" class=\"treesel$sitemap\" target=\"".$this->tree[$cnt][3]."\">".$this->tree[$cnt][1]."</a>";}

                    else {$html.="<a href=\"".$this->tree[$cnt][2]."&".$this->urlparam."=".$GLOBALS[$this->urlparam]."\" class=\"tree$sitemap\" target=\"".$this->tree[$cnt][3]."\">".$this->tree[$cnt][1]."</a>";}
                    $html.="<nobr></td>";
                    }
                }

        /****************************************
         * end row                              *
         ****************************************/
              
                $html .= "</tr>\n";
            
            }
            $cnt++;
            
            //if($onlybranch==1) if ($visible==1 && $this->tree[$cnt][0] < $mindepth && $this->tree[$cnt][5] != $BranchParent) break;
        }

        $html .= "</table>\n";
        return $html;
    }
    
    
    function getPageParent($page) {
        for($num=0; $num<$this->i; $num++)
            if($this->tree[$num][5] == $page)
                return $this->tree[$num][7];
        return false;
    }
    
    function getBranchParent($page_id) {
    
        foreach($this->tree as $subt) {
                if($subt[5]==$page_id && $subt[0]>1){
                     return $this->getBranchParent($subt[7]);
                }elseif($subt[5]==$page_id && $subt[5]==$subt[7]){
                     return $subt[5];
                     break;
                }
            }
        
    }

}


?>