<div class="wrap">
	<form method="post" action="options.php">
	<h2>Scoutnet Kalender</h2>
	<p>Dieses Plugin zeigt Termine und Details aus einem <a href="http://www.scoutnet.de/kalender/">Scoutnet-Kalender</a> als eigene Seite oder in einem Widget an. Welchen Kalender du anzeigen möchtest, bestimmst du mit der <a href="http://www.scoutnet.de/kalender/kurzanleitung.html">Scoutnet-ID</a>.</p>

	<?php settings_fields('snk-opt'); ?>
	<table class="form-table">
	    <tr valign="top">
	        <th scope="row">Scoutnet-ID</th>
	        <td><input type="text" name="scoutnet_kalender_ssid" value="<?php echo get_option('scoutnet_kalender_ssid'); ?>" />
	            <span class="description">
	            	Diese ID wird verwendet wenn du in deinen Seiten oder dem Widget <u>keine</u> ID angibst. Du kannst auch mehrere (und verschiedene) Kalender gleichzeitig nutzen. Gib der jeweiligen Seite oder dem Widget einfach die IDs die du anzeigen m&ouml;chtest mit.
	            </span>
	        </td>
	    </tr>
	</table>

	<table class="form-table">
	    <tr valign="top">
	        <th scope="row">Proxy-Server</th>
	        <td><input type="text" name="scoutnet_kalender_proxy" value="<?php echo get_option('scoutnet_kalender_proxy'); ?>" />
	            <span class="description">
	            	F&uuml;r die Verbindung zum Scoutnet wird dieser Proxy-Server verwendet. Sinnvoll f&uuml;r Hoster, die keine direkten Verbindungen zu externen APIs zulassen. Es wird keine Proxy genutzt (Standart) wenn das Feld leer bleibt.
	            </span>
	        </td>
	    </tr>
	</table>

	<p class="submit">
	    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	</form>

	<h2>In Seiten einbinden</h2>
	<p>Du kannst deine Termin&uuml;bersicht in einer Seite oder einem Beuitrag anzeigen. Shortcode:</p>
	<code>[snk elementcount="5" externalTemplateName="NAME" ssid="3" /]</code>
	<blockquote><ul>
		<li><code>elementcount</code> Gibt dabei die Anzahl der angezeigten Elemente an. Sinnvoll f&uuml;r Seiten ist meistens ein Werte um 30.</li>
		<li><code>externaltemplatename</code> Gibt de Namen des zu verwendenden Templates an. Mehr zu eigenen CSS-Templates findest du in der readme.txt</li>
		<li><code>ssid</code> Die <a href="http://www.scoutnet.de/kalender/include/einbindung-homepage.html">Scoutnet-ID</a> des anzuzeigenden Kalenders</li>
	</ul></blockquote>


</div>