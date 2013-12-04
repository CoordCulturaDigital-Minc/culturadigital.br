<?php
function mrt_sub1(){?>
        <div class=wrap>
                <h2><?php _e('WP - Password Tools') ?></h2>
          <div style="height:299px">
              <?php
echo "<br /><strong>Password Strength Tool</strong>";
?>
<table><tr valign=top><td><form name="commandForm">
Type password: <input type=password size=30 maxlength=50 name=password onkeyup="testPassword(document.forms.commandForm.password.value);" value="">
<br/><font color="#808080">Minimum 6 Characters</td><td><font size="1">  Password Strength:</font><a id="Words"><table><tr><td><table><tr><td height=4 width=150 bgcolor=tan></td></tr></table></td><td>   <b>Begin Typing</b></td></tr></table></a></td></tr></table></td></tr></table></form>
<br /><hr align=left size=2 width=612px>
<?php
echo "<br /><br /><strong>Strong Password Generator</strong><br />";
echo "Strong Password: " . '<font color="red">' . make_password(15) . "</font>";
?>
     </div>
   Plugin by <a href="http://semperfiwebdesign.com/" title="Semper Fi Web Design">Semper Fi Web Design</a>
        </div>
<?php } ?>
