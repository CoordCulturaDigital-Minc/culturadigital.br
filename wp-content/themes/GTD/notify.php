<?php
if($_REQUEST['uids'])
{
	$last_postid = $wpdb->get_var("SELECT max(ID) as ID FROM $wpdb->posts");
	$content = $wpdb->get_var("SELECT post_content FROM $wpdb->posts where ID=\"$last_postid\"");
	$post_title = $wpdb->get_var("SELECT post_title FROM $wpdb->posts where ID=\"$last_postid\"");
	$attached_file = get_post_meta($last_postid, 'attached_file', $single = true);
	$userArray = explode(',' , $_REQUEST['uids']);
	$users_of_blog = get_users_of_blog($userArray[$u]);
	$userInfoArray = array();
	for($i=0;$i<count($users_of_blog);$i++)
	{
		$userInfoArray[$users_of_blog[$i]->user_id] = $users_of_blog[$i];
	}

	for($u=0;$u<count($userArray);$u++)
	{
		$display_name = $userInfoArray[$userArray[$u]]->display_name;	
		$user_email = $userInfoArray[$userArray[$u]]->user_email;

		$message = "
		<p>Dear $display_name,</p>
		<p>Here is the notification for you:</p>
		<p><b>The matter is:</b></p>
		<p>$content</p>
		<p></p>
		<p>Thank You,<Br>$site_email_name</p>
		";

		$site_email = get_option( 'site_email' );
		$site_email_name = get_option( 'site_email_name' );

		$fileatt = $attached_file; // Path to the file
		$fileatt = WP_CONTENT_DIR.str_replace(get_option( 'siteurl' ).'/wp-content','',$fileatt);
		
		$fileatt_type = "application/octet-stream"; // File Type
		$fileatt_name = $attached_file; // Filename that will be used for the file as the attachment
		
		$email_from = get_option( 'site_email' ); // Who the email is from
		$email_subject = $post_title; // The Subject of the email
		$email_txt = $message; // Message that the email has in it
		
		$email_to = $user_email; // Who the email is too
		
		$headers = "From: ".$email_from;
		
		$email_message = chunk_split($message);
		if($fileatt)
		{
			$file = fopen($fileatt,'rb');
			$data = fread($file,filesize($fileatt));
			fclose($file);
			
			$semi_rand = md5(time());
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
			
			$headers .= "\nMIME-Version: 1.0\n" .
			"Content-Type: multipart/mixed;\n" .
			" boundary=\"{$mime_boundary}\"";
			
			$email_message .= "This is a multi-part message in MIME format.\n\n" .
			"--{$mime_boundary}\n" .
			"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
			"Content-Transfer-Encoding: 7bit\n\n" .
			$email_message . "\n\n";
			
			$data = chunk_split(base64_encode($data));
			
			$email_message .= "--{$mime_boundary}\n" .
			"Content-Type: {$fileatt_type};\n" .
			" name=\"{$fileatt_name}\"\n" .
			//"Content-Disposition: attachment;\n" .
			//" filename=\"{$fileatt_name}\"\n" .
			"Content-Transfer-Encoding: base64\n\n" .
			$data . "\n\n" .
			"--{$mime_boundary}--\n";
		}
		$ok = @mail($email_to, $email_subject, $email_message, $headers);
		
/*		if($ok) 
		{
			echo "<font face=verdana size=2>The file was successfully sent!</font>";
		}
		else 
		{
			die("Sorry but the email could not be sent. Please go back and try again!");
		} 
*/	}	
	exit;	
}

?>
<?php
$users_of_blog = get_users_of_blog();
$total_users = count( $users_of_blog );
?>
<script>
	function checkAll(field)
	{
	for (i = 0; i < field.length; i++)
		field[i].checked = true ;
	}
	
	function uncheckAll(field)
	{
	for (i = 0; i < field.length; i++)
		field[i].checked = false ;
	}
	
	function selectCheckBox()
	{
		field = document.getElementsByName('list[]');
		var i;
		ch	= document.getElementById('check');
		if(ch.checked)
		{
			checkAll(field);
		}
		else
		{
			uncheckAll(field);
		}
	}
	
	function recordAction()
	{
		
		var chklength = document.getElementsByName("list[]").length;
		var flag      = false;
		var temp	  ='';
		var useridstr = '';
		for(i=1;i<=chklength;i++)
		{
		   temp = document.getElementById("check_"+i+"").checked; 
		   if(temp == true)
		   {
		   		flag = true;
				useridstr = useridstr+","+document.getElementById("check_"+i+"").value;
			}
		}
		
		if(flag == false)
		{
			alert("Please select atleast one.");
			return false;
		}
		opener.document.getElementById('notification_usersid').value = useridstr.substring(1);
		window.close();
	}
 
</script>
<h3>Notification Page</h3>
<form method="post" action="#" onSubmit="return false;">
<table width="100%" cellpadding="5">
<tr>
  <th width="28" ><input name="check" onClick="return selectCheckBox();" id="check" type="checkbox"></th>
  <th width="120" ><strong>User Name</strong></th>
  <th width="100" ><strong>Email</strong></th>
  <th width="100" ><strong>Type</strong></th>
</tr>
<?php
for($u=0;$u<count($users_of_blog);$u++)
{
	$userId = $users_of_blog[$u]->user_id;
	$user_login = $users_of_blog[$u]->user_login;
	$user_email = $users_of_blog[$u]->user_email;
	$display_name = $users_of_blog[$u]->display_name;
	$meta_value = unserialize($users_of_blog[$u]->meta_value);
	$userTypeArr = array_keys($meta_value);
	$userType =  $userTypeArr[0];
?>
<tr>
  <td align="center"><input name="list[]" id="check_<?php echo $userId;?>" value="<?php echo $userId;?>" type="checkbox"></td>
  <td><?php echo $display_name;?></td>
  <td><?php echo $user_email;?></td>
  <td><?php echo $userType;?></td>
</tr>
<?php
}
?>
<tr>
<td><input name="submit" value="Continue" onclick="return recordAction();" type="button"  /></td>
</tr>
</table>
</form>
