<?php
Function myPageNumber($nowstage,$startpage,$allpage,$nowpage,$pageperstage,$allstage,$argarray=array())
{
  global $PHP_SELF;
  global $QUERY_STRING;
   while (list($key,$value) = each($argarray))
    { global $$value;
     if ($$value>"") $args.="&".$value."=".$$value;
    }
  if(trim($nowpage)>1)
  {
    $links.="&nbsp;<a href='$PHP_SELF?nowstage=1&nowpage=1$args'>&lt;&lt;&lt;</a>&nbsp;\n";
  }
  if(trim($nowstage)>1)
  {
    $links.="&nbsp;<a href='$PHP_SELF?nowstage=".($nowstage-1)."&nowpage=".((($nowstage-1)*$pageperstage)-($pageperstage-1)).$args."'>&lt;&lt;</a>&nbsp;\n";
  }
  for($i=$startpage;$i<=$allpage;$i++)
  {
    if(trim($nowpage)=="")
    {
      $nowpage=$startpage;
    }
    $endpage=(($startpage+$pageperstage)-1);
    if($i>=$startpage&&$i<=$endpage&&$i<=$allpage)
    {
      if($nowpage!=((($nowstage-1)*$pageperstage)+$i)&&$i==$startpage&&$nowpage>$startpage)
      {
        $links=$links."&nbsp;<a href='$PHP_SELF?nowstage=$nowstage&nowpage=".($nowpage-1).$args."'>&lt;</a>&nbsp;\n";
      }
      if(((($nowstage-1)*$pageperstage)+$i)==$nowpage&&((($nowstage-1)*$pageperstage)+$i)<=$allpage)
      {
        $links=$links."&nbsp;<b>".((($nowstage-1)*$pageperstage)+$i)."</b>&nbsp;";
      }
      if(((($nowstage-1)*$pageperstage)+$i)!=$nowpage&&((($nowstage-1)*$pageperstage)+$i)<=$allpage)
      {
        $links=$links."&nbsp;<a href='$PHP_SELF?nowstage=$nowstage&nowpage=".((($nowstage-1)*$pageperstage)+$i).$args."'>".((($nowstage-1)*$pageperstage)+$i)."</a>&nbsp;\n";
      }
      if(($i==$endpage||$i==$allpage)&&$nowpage!=((($nowstage-1)*$pageperstage)+$i)&&$allpage>$nowpage)
      {
        $links=$links."&nbsp; <a href='$PHP_SELF?nowstage=$nowstage&nowpage=".($nowpage+1).$args."'>&gt;</a>&nbsp;\n";
      }
    }
  }
  if($nowstage<$allstage)
  {
    $links=$links. "&nbsp;<a href='$PHP_SELF?nowstage=".($nowstage+1)."&nowpage=".(($nowstage*$pageperstage)+1).$args."'>&gt;&gt;</a>&nbsp;\n";
  }
  if($nowpage<$allpage)
  {
    $links.="&nbsp;<a href='$PHP_SELF?nowstage=".$allstage."&nowpage=".$allpage.$args."'>&gt;&gt;&gt;</a>&nbsp;\n";
  }
return $links;
}
/*
$number="100";				// record results selected from database
$displayperpage="5";				// record displayed per page
$pageperstage="5";				// page displayed per stage
$allpage=ceil($number/$displayperpage);		// how much page will it be ?
$allstage=ceil($allpage/$pageperstage);		// how many page will it be ?
if(trim($startpage)==""){$startpage=1;}
if(trim($nowstage)==""){$nowstage=1;}
if(trim($nowpage)==""){$nowpage=$startpage;}
?>

<font face=tahoma size=2>
Records result from query statement : <b><?=$number;?></b> record.<br>
Want to show <b><?=$displayperpage;?></b> in every page.<br>
Want to show only <b><?=$pageperstage;?></b> page on every stage.<br>
Then there would be <b><?=$allpage;?></b> page of results.<br>
And there would be <b><?=$allstage;?></b> stage of pages.<br><br>
<b>PAGING</b> : <?=myPageNumber($nowstage,$startpage,$allpage,$nowpage,$pageperstage,$allstage);;?><br>
<b>Stage</b> <?=$nowstage." of ".$allstage;?><br><b>Page</b> <?=$nowpage." of ".$allpage;?><br>
<?php
for($i=1;$i<=$displayperpage;$i++)
{
  echo "<br>".((($nowpage-1)*$displayperpage)+$i);
}
?>
</font>
*/?>