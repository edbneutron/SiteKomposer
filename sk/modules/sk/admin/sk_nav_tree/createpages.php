<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><title>Create Pages SQL</title>
Create SQL<br>
<?
foreach($_POST as $key=>$value) $$key=$value;

?>
<form name="form1" method="post" action="">
  <label>parent_id
  <input name="parent_id" type="text" id="parent_id" tabindex="1" size="4">
  </label>

  <br>Title - MID - ca-id<br>
  <label>startnr
  <input name="start_nr" type="text" size="4">
  </label>
 -  
 <label>stopnr
  <input name="stopnr" type="text"  size="4">
  </label>

  <br>additional<br>
  <label>add_nr1
  <input name="add_nr1" type="text"  size="4">
  </label>
 -  
 <label>add_nr2
  <input name="add_nr2" type="text"  size="4">
  </label>
 <br>
 <label>title
 <input name="title" type="text" id="title" size="8">
 </label>
 - 
 <label>link
 <input name="link" type="text" size="8">
 </label>
 <br>
  <label>template
 <input name="template" type="text" size="8">
 </label>
 <br>
 <p>
   <label>
     <input name="type" type="radio" value="navtree" checked>
     navtree</label>
   <br>
   <label>
     <input type="radio" name="type" value="content">
     content</label>
   <br>
   <label>
     <input type="radio" name="type" value="object">
     object</label>
   <br>
 </p>
 <input name="action" type="hidden" id="action" value="doit">
 <br>
 <label>Make it so
 <input type="submit" name="Submit" value="Submit">
 </label>
</form>
<br>
<br>
<pre>
<?
 if($action=="doit"){
 $j=$add_nr1;
 for($i=$start_nr;$i<=$stopnr;$i++) {

switch($type){

case "navtree":
 echo "
  INSERT INTO `sk_nav_tree` ( `id` , `p` , `title` , `depth` , `group_id` , `site_id` , `template` , `path` , `filename` , `linkname` , `mview` , `icon` , `xpos` , `ypos` , `nolink` , `sort_nr` , `user_id` , `last_mod` , `by_user` )
  VALUES (
  '', '".$parent_id."', '".$title.$i."', '4', '0', '1', '".$template."', NULL , 'index.php', '".$link.$i."', '1', NULL , '0', '0', '0', '".$i."', '1', '2000-10-11', '1'
  );<br>";
break;

case "content":
  echo "
  INSERT INTO `sk_content` ( `id` , `mid` , `content_area`)
  VALUES ('', '".$i."', '1');<br>";

break;
case "object":
  echo"
  INSERT INTO `sk_objects` ( `id` , `content_id` , `sort_nr` , `type` , `file` , `attributes` , `objtext` , `user_id` , `last_mod` , `by_user` )
  VALUES (
  '', '".$i."', '10', 'image', '".$title."0".$j."_1.jpg', 'width=\"650\" height=\"218\" align=\"middle\" border=\"0\" space=\"5\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"1\" twidth=\"200\" theight=\"67\" ', '', '5', '2005-11-07', '5'
  );
  INSERT INTO `sk_objects` ( `id` , `content_id` , `sort_nr` , `type` , `file` , `attributes` , `objtext` , `user_id` , `last_mod` , `by_user` )
  VALUES (
  '', '".$i."', '20', 'image', '".$title."0".$j."_2.jpg', 'width=\"650\" height=\"218\" align=\"middle\" border=\"0\" space=\"5\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"1\" twidth=\"200\" theight=\"67\"', '', '5', '2005-11-07', '5'
  );
  INSERT INTO `sk_objects` ( `id` , `content_id` , `sort_nr` , `type` , `file` , `attributes` , `objtext` , `user_id` , `last_mod` , `by_user` )
  VALUES (
  '', '".$i."', '30', 'image', '".$title."0".$j."_3.jpg', 'width=\"650\" height=\"218\" align=\"middle\" border=\"0\" space=\"5\" noflow=\"0\" thumbnail=\"1\" link=\"\" target=\"\" viewer=\"1\" twidth=\"200\" theight=\"67\"', '', '5', '2005-11-07', '5'
  );




  ";


 }

 $j++;
 }
 }

?>
</pre>
</html>