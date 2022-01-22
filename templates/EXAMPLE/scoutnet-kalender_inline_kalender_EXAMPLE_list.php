<?php
/*
	Scoutnet Kalender Template: INLINE (default)
	
	Dir stehen hier alle Inhalte des Kalenders in einem Array zur Verf�gung.
	Z.B.:
	    <?php echo date('d. m. Y', $event->Start); ?>
		<?php echo $event->Title; ?>
		<?php echo $event->Author->get_full_name(); ?>
		<?php var_dump($event); ?>

	Die Einbindung �ber den Shortcode [snk] ist mit den folgenden Parametern m�glich:
		- elementcount		Anzahl auszulesender Elemente
		- externalTemplateName	Name des externen Templates (wie im Widget)

	z.B. [snk elementcount=5 externalTemplateName=MEINNAME]
	
*/

// deutsch, deutscher, am deutschesten
setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');

// Deutsche Zeit
date_default_timezone_set('Europe/Berlin');

// URL-Kuerzung
function short_url($url, $length = 100) {
	$url = parse_url(trim($url));
	$furl = array_filter(explode('/', $url['path']));
	if(count($furl) > 1) $u = '../';
	$url['path'] = $u.array_pop($furl);
	$ausgabe = $url['scheme'].'://'.$url['host'].'/'.$url['path'];
	print substr($ausgabe, 0, $length);
}

foreach($events as $event) { /* @var $event SN_Model_Event */
?>
	<div>
		<div class="date-container">
			<span class="day"><?php echo date('d', $event->Start); ?></span>
			<span class="month"><?php echo htmlentities(strftime('%b', $event->Start)); ?></span>
		</div>	
		<div class="info-container">	
			<?php // Titel mit Link
			if (trim($event->URL)=="") {
				echo "<h3>".$event->Title."</h3>";
				} else {
				echo "<h3><a href=".$event->URL.">".$event->Title."</a></h3>";
				} ?>
			<?php // Beschreibung
			if (trim($event->Description)!="") { echo "<p>" . $event->Description . "</p>"; } ?>
			<small>
				<?php
				// Ort mit PLZ
				if (trim($event->Location)!="") {
					echo "Ort: ";
					if (trim($event->ZIP)!="") {echo $event->ZIP . " ";}
					echo $event->Location;
					echo "<br />";
				}

				// Startzeit
				if (trim($event->Start)!="") {
					echo "Start: " . date('G:i', $event->Start) . "Uhr<br />";
				}

				// Link
				if (trim($event->URL)!="") {
					echo "Link: <a title=\"" . $event->Title . " (" . $event->URL . ")" . "\" href=" . $event->URL . ">";
					short_url($event->URL, 100);
					echo "</a><br />";
				}

				// Autor und zuletzt geaendert
		                if (trim($event->Author->get_full_name())!="") {
		                     if ($event->Last_Modified_At != 0) {
						echo "Autor: " . $event->Author->get_full_name() . "(ge&auml;ndert am " . date('d.m.Y', $event->Last_Modified_At) . ")";
					} else {
						echo "Autor: " . $event->Author->get_full_name() . "(ge&auml;ndert am " . date('d.m.Y',$event->Created_At) . ")";
					}
				}

				if (trim($event->Author->get_full_name())!="") {
					echo "Autor: " . $event->Author->get_full_name() . "(ge&auml;ndert am " . date('d.m.Y', $event->Last_Modified_At) . ")";
				}
				?>
			</small>
		</div>
	</div>
	<br style="clear: both;" />
	<br /><br />
<?php } ?>