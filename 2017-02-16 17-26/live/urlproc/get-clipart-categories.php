<table align="center" width="100%" cellpadding="0" cellspacing="4">

<tr>

<?php

 $i = 1;

 $clipart_cat = mysql_query('Select * from tpt_module_bandclipartcategory where parent_id = 0 and category_status != 0 order by category_name ASC') or die(mysql_error());

 while($clipart_cat_result = mysql_fetch_array($clipart_cat)) { ?>

 

 <?php

 $check_qry = mysql_query('Select * from tpt_module_bandclipartcategory where parent_id = '.$clipart_cat_result['id'].' and category_status != 0 order by category_name ASC');

 $num = mysql_num_rows($check_qry);

 ?>

  <td align="center" style="padding:5px; background-color:#e2d7c2; color:#000; border: solid 1px #5a4b42;text-transform: capitalize;font-family: Arial;">

   <a href="javascript:void(0);" <?php  if($num != 0) { ?> onclick="show_subcategory1('<?php echo $clipart_cat_result['id']; ?>','<?php echo $i; ?>');" <?php  } else { ?>onclick="list_clipart_ori1('<?php echo $clipart_cat_result['id']; ?>');"<?php } ?> style="text-decoration:none; font-weight:bold;"><?php echo $clipart_cat_result['category_name']; ?></a><div id="result<?php echo $clipart_cat_result['id']; ?>" <?php if($i > 5) { echo 'class="ttt-corner"'; } else { echo 'class="ttt"';} ?> align="left"></div></td>

      <?php if($i > 4) { echo '</td></tr><tr><td colspan="6" height="5"></td></tr><tr>'; $i = 0; } ?>

<?php 

 $i++; 

 

 }

?>

</tr>

</table>