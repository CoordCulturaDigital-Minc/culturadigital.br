<?php
/*
* cdigital_embbed.php
*
* Copyright (C) 2010  Ministério da Cultura Brasileira
* Copyright (C) 2010  Marcos Maia Lopes <marcosmlopes01@gmail.com>
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* Plugin name: CulturaDigital Embbed Shortcode
* Plugin author: Marcos Maia Lopes
* Plugin url: http://xemele.cultura.gov.br/
*/

function cdigital_embed($args) {
  extract($args);

  $width = ($width) ? $width : 500;
  $height = ($height) ? $height : 300;

  $url = "config={'clip':{'url':'{$link}', 'autoPlay':false, 'autoBuffering':false}}";
  $player = '<object id="flowplayer" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $width . '" height="' . $height . '"><param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.5.swf" /><param name="flashvars" value="' . $url . '" /><embed type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '" src="http://releases.flowplayer.org/swf/flowplayer-3.2.5.swf" flashvars="' . $url . '"/></object>';

  return $player;
}

add_shortcode('cdigital_embed', 'cdigital_embed');
