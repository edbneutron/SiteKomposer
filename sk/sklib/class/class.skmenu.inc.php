<?php
/***************************************
** Title........: SK-Menu class
** Filename.....: class.skmenu.inc
** Author.......: Edgar Bueltemeyer
** Version......: 1.5
** Notes........: This class generates various menu-styles for navigation
** Notes........: Currently implemented:
** Notes........: TreeMenu, Css-menu, xml-sitemap
** Notes........: 
** Notes........: 
** Notes........: 
** Last changed.: 11/11/2008
** Last change..: 
***************************************/

// new version of sk-menu class
// integrates new phplayers class
// creates menu-structure-file
class skmenu{
    var $debug = 0;
    var $admin = 0;
    var $layerscount;    // it counts layers for all menus
    var $tree;
    var $treecnt;var $temptree;
    var $layer_label;
    
    
/**
 * skmenu::skmenu_new()
 * set variables
 * @return 
 */
function skmenu($admin=0){
    $this->admin=$admin;
    DEBUG_out(2,"debug2","skmenu_new class initiated");
}// skmenu_new

/**
 * skmenu::getfromdb()
 * 
 * @return 
 */
function getfromdb($site_id=0){
    global $skdb;
    global $current_site_id;
    global $url_par;
    /* with some changes */
    /* changed to read menu from Database */
    /* by Edgar Bueltemeyer               */
    /*********************************************/
    /* read file to $tree array                  */
    /* tree[x][0] -> tree level                  */
    /* tree[x][1] -> item text                   */
    /* tree[x][2] -> item link                   */
    /* tree[x][3] -> link target                 */
    /*********************************************/

    $maxlevel = 0;
    $cnt = $this->layerscount + 1;
    $firstlevelcnt = 0;
    if ($site_id) $current_site_id=$site_id;
    if (!isset($current_site_id) && !isset($site_id)) $current_site_id=1;
    if($this->admin==1):
    $menuqry = "SELECT * FROM sk_nav_tree WHERE site_id=$current_site_id ORDER BY sort_nr, p ASC";
    else:
    $menuqry = "SELECT * FROM sk_nav_tree WHERE mview='1' AND site_id=$current_site_id ORDER BY sort_nr ASC";
    endif;
          //echo $menuqry;
          $result = $skdb->Execute($menuqry);
          if ($result == false) DEBUG_out(1,"error1","Menu-select failed! ".$skdb->Errormsg());
            
          $cnt = $this->layerscount + 1;

              while (!$result->EOF && $result!=False) {
                $item_id=$result->fields['id'];
                $parent_id=$result->fields['p'];
                if ($item_id==$parent_id)$parent_id=0;
                
                $this->temptree[$parent_id][$item_id][0] =$result->fields['depth'];  //depth of node
                $this->temptree[$parent_id][$item_id][1] = $result->fields['linkname']; // Link-Label
                if($result->fields["mview"]==0) $this->temptree[$parent_id][$item_id][1] .= "&nbsp;<i>- versteckt</i>";
		//create link
                if($result->fields["nolink"]==0 || $this->admin==1){
					if(defined("MOD_REWRITE") && MOD_REWRITE==1 && $_REQUEST['edit']!=1) {
						$this->temptree[$parent_id][$item_id][2] = $result->fields['path']."index-".$result->fields['id'].$url_par.".html"; //Link with Path and menu_id
						$this->temptree[$parent_id][$item_id][2] = $result->fields['path'].safeURL($result->fields['linkname'])."_index-".$result->fields['id'].$url_par.".html"; //Link with Title Path and menu_id
					}else{
						$this->temptree[$parent_id][$item_id][2] = $result->fields['path'].$result->fields['filename']."?mid=".$result->fields['id'].$url_par; //Link with Path and menu_id
					}
                }else{
                	$this->temptree[$parent_id][$item_id][2] ="";
                }
                $this->temptree[$parent_id][$item_id][3] = $result->fields['id']; // Link-ID
                $this->temptree[$parent_id][$item_id][4] = $result->fields['p']; // Link-Parent-Pointer
                $this->temptree[$parent_id][$item_id][5] = $result->fields['xpos']; // Layer x-position
                $this->temptree[$parent_id][$item_id][6] = $result->fields['icon']; // depth
                $this->temptree[$parent_id][$item_id][7] = $result->fields['sort_nr']; // sort-number
                $this->temptree[$parent_id][$item_id][8] = $result->fields['title']; // title
				$this->temptree[$parent_id][$item_id][9] = $result->fields['mview']; // visibility
                if ($this->temptree[$parent_id][$item_id][0] > $maxlevel) $maxlevel = $this->temptree[$parent_id][$item_id][0];
                $result->MoveNext();
              	$cnt++;
              }
    DEBUG_out(4,"debug4","....loaded temptree DB<br>".var_export($this->temptree,1));
    $this->sorttree(0,0,0,1);
    $this->treecnt=count($this->tree);
    DEBUG_out(4,"debug3","count this->tree:".$this->treecnt);
    DEBUG_out(4,"debug4","....loaded structure from DB<br>".print_array($this->tree));


} // getfromdb


/**
 * sorttree()
 * sorts tree from db
 * @param $parcat
 * @param $level
 * @param $maxlevel
 * @param $cnt
 * @param $horizontal
 * @return 
 */
function sorttree($parcat,$level,$maxlevel,$cnt,$horizontal=0){

         if(isset($this->temptree[$parcat])) $list=$this->temptree[$parcat];else $list=0;
         if(is_array($list)){
                while(list($key,$val)=each($list)){
                    $this->tree[$cnt]=$this->temptree[$parcat][$key];
                    $this->layer_label[$cnt] = "L" . $key;  // Link-ID as Label
                    $cnt++;
                      if ((isset($this->temptree[$key])) AND (($maxlevel>$level+1) OR ($maxlevel=="0"))){
                         $cnt=$this->sorttree($key,$level+1,$maxlevel,$cnt,$horizontal);
                      };
                };
                }
                return $cnt;
}// sorttree


/**
 * skmenu::cssmenu()
 * 
 * @param $theme
 * @param $title
 * @param $format
 * @param $page_id
 * @param $mindepth
 * @param $onlybranch
 * @return 
 */
function css_menu($mindepth=0,$maxdepth=10,$onlybranch=0,$parent_links=1,$exclude_branch_id=0,$current_page_id=0,$view_all=0,$sel_branch=0,$parent_id=0,$current_sub=0, $css_ul_id="cssmenu"){
    $output="";
    if(is_array($this->tree)) reset($this->tree);
    $csstree=$this->tree;
    $current_page_nr=$this->getPageTreeNr($current_page_id);
    $lastdepth=1;
    $menucnt=count($csstree);
    $branch_ary=$this->getBranch_TreeIds($current_page_id);
    DEBUG_out(4,"debug4","cssmenu:current_page_id=$current_page_id".print_array($branch_ary));
    DEBUG_out(4,"debug4","parent_id=".$this->getPageParent($current_page_id));
    if($onlybranch==1){
	    if($this->tree[$current_page_nr][0]==$maxdepth){
		 	$gettree_id=$this->getPageParent($current_page_id);
			
	    }else{
	    	$gettree_id=$current_page_id;
	    }
		$leaf=1;
        if($current_sub==0){
        	$csstree=$this->getSubtree($this->getBranchParent($gettree_id));
        }else {
            //if current page is above one level exit
             if($this->tree[$current_page_nr][0]<$mindepth-1) return;
             $csstree=$this->getSubtree($gettree_id);
        }
	 	$menucnt=count($csstree)-1;
    }
    if($parent_id!=0 && !in_array($parent_id,$branch_ary)) return;
    
    DEBUG_out(4,"debug4","csstree-count:".count($csstree));
    //$output.=print_array($csstree);
    if(count($csstree)==0) return;
    for($cnt=1;$cnt <= $menucnt;$cnt++) {
	      $class="";$css_id="";
	      $node=$csstree[$cnt];
	      if(isset($csstree[$cnt+1])) $nextnode=$csstree[$cnt+1];else $nextnode="";
	      $depth=$node[0];
	      DEBUG_out(4,"debug4","csstree:id:$node[3] parent:$node[4] $parent_id  $depth < $mindepth ".$this->getPageParent($node[3]));
	      //check if node should be displayed
	      if(!($depth < $mindepth) && !($depth > $maxdepth) && 
	      	($parent_id==0 || $parent_id==$this->getPageParent($node[3]) || ($node[4]==$this->getPageParent($node[3]) && $leaf==1) )
	      	) {
	      		
		      if(isset($nextnode[0])) $nextdepth=$nextnode[0];else $nextdepth=1;
		      if($node[3]==$current_page_id || ($sel_branch==1 && in_array($node[3],$branch_ary))){ 
		      	$class="class=\"selected\"";  
		      	$lisubclass="class=\"subselected\"";
		      	$liclass="class=\"selected\"";
	      	  }else {
		      	$class="class=\"normal\"";
				$lisubclass="class=\"submenu\"";
				$liclass="";
	      	  }
		      if(in_array($node[3],$branch_ary)) $css_id=" id=\"submenushow\""; else $css_id=" id=\"submenuhide\""; 
		      DEBUG_out(4,"debug4","lastdepth - depth - nextdepth - mindepth - maxdepth:".$lastdepth." - ".$depth." - ".$nextdepth." - ".$mindepth." - ".$maxdepth);
		
		      if($depth<$lastdepth) { $flag=3;for($cx=1;$cx<=($lastdepth-$depth);$cx++) $menuout.="</div></ul>\n</li>\n";}
		
		      if($depth<$nextdepth && !($nextdepth > $maxdepth)) $menuout.="<li $lisubclass".$css_id.">"; else $menuout.="<li $liclass>";
		
		      if($node[2]>"") $menuout.="<a href=\"$node[2]\" $class>$node[1]</a>";else  $menuout.="<div id=\"nolink\">$node[1]</div>\n";
		
		      if($depth<$nextdepth && !($nextdepth > $maxdepth)) { $flag=1; $menuout.="\n<ul><div id=\"uldiv\">\n";}
		      if($flag!=1) $menuout.="</li>\n";
		      if($flag==1) $flag=0;
		      if($cnt==$menucnt) {
			      //if($flag!=1) $menuout.="</li>\n";
			      if($depth>1) $menuout.="</ul></li>\n";
			      //$menuout.="</ul>\n</li>\n";
		      }
		      $lastdepth=$depth;
	      }
    } // for($cnt=1;$cnt...
    if(strlen($menuout)>0){
      	//if($flag!=1) $menuout.="</li>\n";
    	$output.="<ul id=\"".$css_ul_id."\">\n$menuout\n</ul>\n";
    }

    if(is_array($csstree)) reset($csstree);
    DEBUG_out(3,"debug3","cssmenu:".$output);
    return $output;
  } // end class
  

/**
 * skmenu::treemenu()
 * 
 * @param $theme
 * @param $title
 * @param $format
 * @param $page_id
 * @param $mindepth
 * @param $onlybranch
 * @return 
 */
 /* remark: only used for admin, replace with cssmenu */
  
 function treemenu($theme="default",$title="",$format=0,$page_id=0,$mindepth=0,$onlybranch=0,$parent_links=1,$exclude_branch_id=0,$current_page_id=0,$view_all=0,$sel_branch=0){
    require_once(CLASS_PATH."treemenu/treemenu.inc.php");
    DEBUG_out(3,"bench3","include treemenu/treemenu.inc.php:");
    if($exclude_branch_id!=0)
          if($exclude_branch_id == $this->getBranchParent($current_page_id)){return;}
     DEBUG_out(4,"debug3","CurrentPage: $current_page_id : ".$exclude_branch_id."=".$this->getBranchParent($current_page_id));
    $imgpath=SKRES_URL."img/tree/".$theme."/";
    if (is_array($this->tree)) reset($this->tree);
    //
    $treemenu=new TreeMenu("_value","",$imgpath,$this->admin);
    if (is_array($this->tree)){while (list($index, $submenu) = each($this->tree) ) {
      $treemenu->addNode($submenu[0], $submenu[1], $submenu[2],"",$submenu[3],$submenu[7],$submenu[4]);
    }
    }
    $treemenu->branch_ary=$this->getBranch_TreeIds($current_page_id);
    switch($format) {
    case 0:         // normal Tree-Menu
        return $treemenu->getHTMLTable($mindepth,$page_id,$sel_branch);
        break;
    case 1:        // expanded text-only
        return $treemenu->getHTMLTable_exto($page_id,$mindepth,$onlybranch,$parent_links,$current_page_id,$view_all,$sel_branch);
        break;
    }
    

}//function treemenu


/**
 * skmenu::xml_sitemap()
 * 
 * @param $theme
 * @param $title
 * @param $format
 * @param $page_id
 * @param $mindepth
 * @param $onlybranch
 * @return 
 */
/* remark: add urls in mod_rewrite form */

function xml_sitemap(){
    global $current_page_id;
    $output="";
    if(is_array($this->tree)) reset($this->tree);
    $xmltree=$this->tree;
    $current_page_nr=$this->getPageTreeNr($current_page_id);
    $lastdepth=1;
    $menucnt=count($xmltree);
    $branch_ary=$this->getBranch_TreeIds($current_page_id);
    DEBUG_out(4,"debug3","xml_sitemap-count:".count($xmltree));
    //$output.=print_array($csstree);
    if(count($xmltree)==0) return;
    $output.='<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    for($cnt=1;$cnt <= $menucnt;$cnt++) {
      $node=$xmltree[$cnt];
 	  $menuout.='  <url>'."\n".
		    	'	<loc>'.SITE_URL.$node[2].'</loc>'."\n".
 	  			'	<lastmod>'.date('Y-m-d').'</lastmod>'."\n".
		    	'	<changefreq>weekly</changefreq>'."\n".
		    	'	<priority>0.8</priority>'."\n".
	   			'  </url>'."\n";
    }
	if(strlen($menuout)>0){
    	$output.="\n$menuout\n";
    	$output.='</urlset>';
    }
    if(is_array($xmltree)) reset($xmltree);
    DEBUG_out(4,"debug3","xml_sitemap:".$output);
    return $output;
  }
  


   function bread_crumb($page_id,$depth) {

         $out_string="";
         $branch_ary=$this->getBranch_TreeNumbers($page_id);
         $ary_cnt=count($branch_ary);
         if ($ary_cnt<$depth) return;
         DEBUG_out(4,"debug4","ary_cnt:".$ary_cnt);
         for($idx=0;$idx<$ary_cnt;$idx++) {
           $cnt=$branch_ary[$idx];
           $str="&nbsp;<a href=\"".$this->tree[$cnt][2]."\"";
           if($this->tree[$cnt][3]==$page_id){ $str.="class=\"breadcrumbsel\">";} else{ $str.="class=\"breadcrumb\">";}
           if($idx<($ary_cnt-1)) $str.="&gt;&nbsp;";
           $str.=$this->tree[$cnt][1]."</a>";
           $out_string=$str.$out_string;

         }
         return substr($out_string,6,strlen($out_string));

    } // function bread_crumb


// ******************
// Utility Functions
// ******************

 function getPageTreeNr($page_id) {

        for($num=0; $num<=$this->treecnt; $num++)
            if(isset($this->tree[$num][3]) && $this->tree[$num][3] == $page_id)
                return $num;
        return false;
    } // function getPageTreeNr


 function getBranch_TreeNumbers($page_id) {
    $curr_tree_nr=$this->getPageTreeNr($page_id);
    $branch_ary[]=$curr_tree_nr;
    $last_id=$page_id;
    $curr_id=$this->tree[$curr_tree_nr][4];
    DEBUG_out(4,"debug4","last_id:".$last_id." page_id:".$page_id." curr_id:".$curr_id." curr_tree_nr:".$curr_tree_nr);
    while ($last_id<>$curr_id) {
         $curr_tree_nr=$this->getPageTreeNr($curr_id);
         $branch_ary[]=$curr_tree_nr;
         $last_id=$curr_id;
         $curr_id=$this->tree[$curr_tree_nr][4];
    }
    DEBUG_out(4,"debug4","Branch-Tree_Numbers:".print_array($branch_ary));
    return $branch_ary;
 } //function getBranch_TreeNumbers

  function getBranch_TreeIds($page_id) {
    $curr_tree_nr=$this->getPageTreeNr($page_id);
    $curr_id=$page_id;
    $branch_ary[]=$curr_id;
    $last_id=$page_id;
    $curr_id=$this->getPageParent($curr_id);
    while ($last_id<>$curr_id) {
         $branch_ary[]=$curr_id;
         $last_id=$curr_id;
         $curr_id=$this->getPageParent($curr_id);
    }
    DEBUG_out(4,"debug4","last_id:".$last_id." page_id:".$page_id." curr_id:".$curr_id);
    DEBUG_out(4,"debug4","Branch-Tree_ids:".print_array($branch_ary));
    return $branch_ary;
 } //function getBranch_TreeNumbers

 function getPageParent($page_id) {

        for($num=0; $num<=$this->treecnt; $num++)
            if(isset($this->tree[$num][3]) && $this->tree[$num][3] == $page_id)
                return $this->tree[$num][4];
        return false;
    } // function getPageParent

    
    function getBranchParent($page_id) {
        if (is_array($this->tree))
        for($num=0; $num<=$this->treecnt; $num++) {
            if(isset($this->tree[$num][3]) && $this->tree[$num][3]==$page_id) {
                if($this->tree[$num][3]==$this->tree[$num][4]){
                     return $this->tree[$num][3];
                     break;
                }else{
                     return $this->getBranchParent($this->tree[$num][4]);
                     break;
                }
            }
        }
        
    } // function getBranchParent


/**
 * skmenu::getSubTreeNr()
 * 
 * returns an array containing all tree_array numbers under this page_id (including current page_id)
 *
 * @param $page_id
 * @return $subtree_nr_array
 */
    function getSubTreeNr($page_id){
    
        $parent_tree_nr=$this->getPageTreeNr($page_id);
        $subtree_nr_ary[]=$parent_tree_nr;
        
        for($num=$parent_tree_nr+1; $num<=$this->treecnt; $num++) {
            if($this->tree[$num][0]<=$this->tree[$parent_tree_nr][0]){ 
                break;
            }else{
             $subtree_nr_ary[]=$num;   
            }
            
        }
        DEBUG_out(4,"debug4","get subtree_nr_ary:".print_array($subtree_nr_ary));
        return $subtree_nr_ary;
    
    }//function getSubtreeNr
    
/**
 * skmenu::getSubtree()
 * 
 * returns an array containing all the sub_tree under this page_id (including current page)
 *
 * @param $page_id
 * @return $subtree
 */
    function getSubtree($page_id){
    
        $parent_tree_nr=$this->getPageTreeNr($page_id);
	unset($subtree);
	$branch_ary=$this->getBranch_TreeIds($page_id);
	$subtree=array();
        $subtree[]=$this->tree[$parent_tree_nr];
        
        for($num=$parent_tree_nr+1; $num<=$this->treecnt; $num++) {
		//DEBUG_out(3,"debug4","getSubtree:this->tree[$num][0]".$this->tree[$num][0]."this->tree[$parent_tree_nr][0]".$this->tree[$parent_tree_nr][0]);
            if($this->tree[$num][0]<=$this->tree[$parent_tree_nr][0]){ 
                break;
            }else{
		//if($this->tree[$num] && in_array($this->tree[$num][4],$branch_ary))
             $subtree[]=$this->tree[$num];   
	     //DEBUG_out(4,"debug4","getSubtree:".$this->getBranchParent($this->tree[$num][3]).$this->tree[$num][3].$this->tree[$num][4].$this->tree[$num][1]);
            }
            
        }
        //DEBUG_out(4,"debug4","getSubtree:".print_array($subtree));
        return $subtree;
    
    }//function getSubtreeNr



} // class skmenu_new

?>