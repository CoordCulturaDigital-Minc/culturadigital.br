- Fixed audio CAPTCHA link URL, it did not work properly on Safari 3.2.1 (Mac OS X 10.5.6). 
safari was trying to download securimage.wav.php instead of securimage.wav

- Note: the proper way the audio CAPTCHA is supposed to work is like this: a dialog pops up, You have chosen to open:
secureimage.wav What should (Firefox, Safari, IE, etc.) do with this file? Open with: (Choose) OR Save File. Be sure to select open, then it will play in WMP, Quicktime, Itunes, etc.


Here is the change should you wish to do it manually....
 
edit captcha-secureimage/securimage_play.php

Change:
header('Content-Disposition: attachment; name="securimage.wav"'); 

To:
header('Content-Disposition: attachment; filename="securimage.wav"'); 