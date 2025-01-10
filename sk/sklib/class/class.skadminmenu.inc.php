<?php
/***************************************
** Title........: SK-Adminmenu class
** Filename.....: class.skadminmenu.inc.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.5
** Notes........:
** Last changed.: 15/Feb/2005
** Last change..: 
***************************************/



class adminmenu {

             var $modules;
             var $cmsmod;
             var $systemmod;
         var $utilmod;
             var $debug = 1;
             var $current_site;
     function adminmenu(){
              global $skdb;
              global $Messages;
              global $sess_username;
              
              $this->current_site = new sksite($_SESSION["sess_site_id"]);
              $moduleqry = "SELECT  DISTINCTROW sk_modules .  * , sk_users.uname, sk_groups_modules_link.type, sk_groups_sites_link.site_id
                            FROM ( ( ( sk_groups
                            INNER  JOIN sk_groups_modules_link ON sk_groups.groupid = sk_groups_modules_link.groupid
                            INNER  JOIN sk_groups_sites_link ON sk_groups.groupid = sk_groups_sites_link.groupid )
                            INNER  JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid )
                            INNER  JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid )
                            INNER  JOIN sk_modules ON sk_groups_modules_link.mid = sk_modules.id
                            WHERE  ( sk_users.uid = ".$_SESSION["sess_uid"].") AND ( sk_groups_modules_link.type =  'A' ) 
                                  AND ( sk_modules.type =  'module' ) 
                                  AND ( sk_groups_sites_link.site_id=".$_SESSION["sess_site_id"].")
                            ORDER  BY sort
                            LIMIT 0, 30";
                  $this->modules = $skdb->Execute($moduleqry);
                  if ($this->modules == false) $Messages.=("module-section query failed<br>");
              
              $cmsqry = "SELECT  DISTINCTROW sk_modules .  * , sk_users.uname, sk_groups_modules_link.type, sk_groups_sites_link.site_id
                            FROM ( ( ( sk_groups
                            INNER  JOIN sk_groups_modules_link ON sk_groups.groupid = sk_groups_modules_link.groupid
                            INNER  JOIN sk_groups_sites_link ON sk_groups.groupid = sk_groups_sites_link.groupid )
                            INNER  JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid )
                            INNER  JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid )
                            INNER  JOIN sk_modules ON sk_groups_modules_link.mid = sk_modules.id
                            WHERE  ( sk_users.uid = ".$_SESSION["sess_uid"].") AND ( sk_groups_modules_link.type =  'A' ) 
                                  AND ( sk_modules.type =  'cms' ) 
                                  AND ( sk_groups_sites_link.site_id=".$_SESSION["sess_site_id"].")
                            ORDER  BY sort
                            LIMIT 0, 30";
                  $this->cmsmod = $skdb->Execute($cmsqry);
                  if ($this->cmsmod == false) $Messages.=("cms-section query failed<br>");
              
              $systemqry = "SELECT  DISTINCTROW sk_modules .  * , sk_users.uname, sk_groups_modules_link.type, sk_groups_sites_link.site_id
                            FROM ( ( ( sk_groups
                            INNER  JOIN sk_groups_modules_link ON sk_groups.groupid = sk_groups_modules_link.groupid
                            INNER  JOIN sk_groups_sites_link ON sk_groups.groupid = sk_groups_sites_link.groupid )
                            INNER  JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid )
                            INNER  JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid )
                            INNER  JOIN sk_modules ON sk_groups_modules_link.mid = sk_modules.id
                            WHERE  ( sk_users.uid = ".$_SESSION["sess_uid"].") AND ( sk_groups_modules_link.type =  'A' ) 
                                  AND ( sk_modules.type =  'system' ) 
                                  AND ( sk_groups_sites_link.site_id=".$_SESSION["sess_site_id"].")
                            ORDER  BY sort
                            LIMIT 0, 30";
                  $this->systemmod = $skdb->Execute($systemqry);
                  if ($this->systemmod == false) $Messages.=("system query failed<br>");
          
          $utilqry = "SELECT  DISTINCTROW sk_modules .  * , sk_users.uname, sk_groups_modules_link.type, sk_groups_sites_link.site_id
                            FROM ( ( ( sk_groups
                            INNER  JOIN sk_groups_modules_link ON sk_groups.groupid = sk_groups_modules_link.groupid
                            INNER  JOIN sk_groups_sites_link ON sk_groups.groupid = sk_groups_sites_link.groupid )
                            INNER  JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid )
                            INNER  JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid )
                            INNER  JOIN sk_modules ON sk_groups_modules_link.mid = sk_modules.id
                            WHERE  ( sk_users.uid = ".$_SESSION["sess_uid"].") AND ( sk_groups_modules_link.type =  'A' ) 
                                  AND ( sk_modules.type =  'util' ) 
                                  AND ( sk_groups_sites_link.site_id=".$_SESSION["sess_site_id"].")
                            ORDER  BY sort
                            LIMIT 0, 30";
                  $this->utilmod = $skdb->Execute($utilqry);
                  if ($this->utilmod == false) $Messages.=("util query failed<br>");
          
              DEBUG_out(4,"query",$moduleqry);
              DEBUG_out(4,"query",$cmsqry);
              DEBUG_out(4,"query",$systemqry);
          DEBUG_out(4,"query",$utilqry);
              } // End function adminmenu
              
        function toolbar($left=10,$top=30,$alvis=2,$page_edit=0,$messages="",$position="absolute",$dragable=0) {
            if ($alvis==0 && !isset($sess_alvis)) $alvis=2;
            if (isset($_SESSION["sess_alvis"]) && $page_edit==0 && $alvis==0) $alvis=$_SESSION["sess_alvis"];

	    global $imgurl; global $themeurl;
            global $title;  global $skurl;
            global $skresurl; global $imgurl;
            global $server_address; global $site_url;
            global $mbmv1; global $mbmv2;
            global $vis1;  global $vis2;
	    global $vis3;  global $vis4;
            global $modcount; global $cmscount;
            global $syscount; global $utilcount;
	    global $modmenu;  global $cmsmenu; 
            global $sysmenu;  global $utilmenu;
	    global $leftp;    global $topp;
            global $lposition; global $window_title;
	    $topp=$top;$leftp=$left;$lposition=$position;
            $tpl = new rhtemplate;
            $tpl->load_file('adminmenu', SKRES_PATH.'skinz/'.$GLOBALS['theme'].'/tpl/adminmenu.html');
            $title=$window_title;
            $themeurl=SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';
            $imgurl=$themeurl.'img/adminmenu/';
            $skurl=SK_URL;
            $skresurl=SKRES_URL;
            $sitename=$this->current_site->attributes["name"];
            $site_url=$this->current_site->attributes['site_url'];

            // Modules
            $modmenu='<table width="50" border="0" cellspacing="0" cellpadding="3">';
             $result=$this->modules;
             $modcount=0;
               while (!$result->EOF AND !$result == false) {
               $modmenu.='<tr><td align="center"><a class="smallblack" target="_top" href="'.SK_URL.'modules/'.$result->fields["dirname"].'/'.$result->fields["link"].'?alvis=1"><img src="'.SK_URL.'modules/'.$result->fields["dirname"].'/'.$result->fields["icon"].'_small.gif" width="50" height="50" border="0" alt=""><br>
                '.$result->fields["title"].'</a></td>
               </tr>';
               $modcount++;
               $result->MoveNext();
                 }
            $modmenu.='</table>';
            //CMS
            $cmsmenu='<table width="50" border="0" cellspacing="0" cellpadding="3">';
              $result= $this->cmsmod;
              $cmscount=0;
                while (!$result->EOF AND !$result == false) {
                $cmsmenu.='<tr>
                <td align="center"><a class="smallblack" target="_top" href="'.SK_URL.'modules/'.$result->fields["dirname"].'/'.$result->fields["link"].'?alvis=2"><img src="'.SK_URL.'modules/'.$result->fields["dirname"].'/'.$result->fields["icon"].'_small.gif" width="50" height="50" border="0" alt=""><br>
                '.$result->fields["title"].'</a></td>
                </tr>';
                $cmscount++;
                $result->MoveNext();
                  }
            $cmsmenu.='</table>';
            //System
            $sysmenu='<table width="50" border="0" cellspacing="0" cellpadding="3">';
              $result= $this->systemmod;
              $syscount=0;
                while (!$result->EOF AND !$result == false) {
                $sysmenu.='<tr>
                <td align="center"><a class="smallblack" target="_top" href="'.SK_URL.'modules/'.$result->fields["dirname"].'/'.$result->fields["link"].'?alvis=3"><img src="'.SK_URL.'modules/'.$result->fields["dirname"].'/icon_small.gif" width="50" height="50" border="0" alt=""><br>
                '.$result->fields["title"].'</a></td>
                </tr>';
                $syscount++;
                $result->MoveNext();
                  }
            $sysmenu.='</table>';
        //Utilities
            $utilmenu='<table width="50" border="0" cellspacing="0" cellpadding="3">';
              $result= $this->utilmod;
              $utilcount=0;
                while (!$result->EOF AND !$result == false) {
                $utilmenu.='<tr>
                <td align="center"><a class="smallblack" target="_top" href="'.SK_URL.'modules/'.$result->fields["dirname"].'/'.$result->fields["link"].'?alvis=4"><img src="'.SK_URL.'modules/'.$result->fields["dirname"].'/icon_small.gif" width="50" height="50" border="0" alt=""><br>
                '.$result->fields["title"].'</a></td>
                </tr>';
                $utilcount++;
                $result->MoveNext();
                  }
            $utilmenu.='</table>';
        if($utilcount!=0)$tpl->register('adminmenu', 'utilcount');
        
            switch($alvis) {
             case "12":
                 $mbmv1="hidden";
                 $mbmv2="visible";
         $vis1="none";
         $vis2="inline";
         $vis3="none";
         $vis4="none";
             break;
             case "1":
                 $mbmv1="visible";
                 $mbmv2="hidden";
         $vis1="inline";
         $vis2="none";
         $vis3="none";
         $vis4="none";
             break;
             case "2":
                 $mbmv1="visible";
                 $mbmv2="hidden";
         $vis1="none";
         $vis2="inline";
         $vis3="none";
         $vis4="none";
             break;
             case "3":
                 $mbmv1="visible";
                 $mbmv2="hidden";
         $vis1="none";
         $vis2="none";
         $vis3="inline";
         $vis4="none";
             break;
         case "4":
                 $mbmv1="visible";
                 $mbmv2="hidden";
         $vis1="none";
         $vis2="none";
         $vis3="none";
         $vis4="inline";
             break;
            }
            


            $tpl->register('adminmenu', 'themeurl');
            $tpl->register('adminmenu', 'title');
            $tpl->register('adminmenu', 'skurl');
            $tpl->register('adminmenu', 'skresurl');
            $tpl->register('adminmenu', 'imgurl');
            $tpl->register('adminmenu', 'server_address');
            $tpl->register('adminmenu', 'site_url');
            $tpl->register('adminmenu', 'mbmv1');
            $tpl->register('adminmenu', 'mbmv2');
            $tpl->register('adminmenu', 'vis1');
        $tpl->register('adminmenu', 'vis2');
        $tpl->register('adminmenu', 'vis3');
        $tpl->register('adminmenu', 'vis4');
        $tpl->register('adminmenu', 'modmenu');
            $tpl->register('adminmenu', 'cmsmenu');
            $tpl->register('adminmenu', 'sysmenu');
        $tpl->register('adminmenu', 'utilmenu');
        $tpl->register('adminmenu', 'leftp');
        $tpl->register('adminmenu', 'topp');
            $tpl->register('adminmenu', 'lposition');
        
        $tpl->parse('adminmenu');
        //$tpl->parse_if('adminmenu','modcount,cmscount,syscount,utilcount');
            return $tpl->return_file('adminmenu');

        } // End function toolbar()
        

              
        } // End class adminmenu
             ?>