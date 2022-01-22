<?php
/*
	Scoutnet Kalender Template: EVENTLISTE (default)
	Erzeugt die LISTE der Events, jedes Event wird hierin mit der Datei *_event.php angezeigt.

		ACHTUNG!
		Dieses Template bindet auch Remote-HTML-Code ein, wenn das
		jemand in den Kalender eingetragen hat.
*/

// Deutsche Zeit
date_default_timezone_set('Europe/Berlin');

	// Wenn der Aufruf (noch) nicht per AJAX kam, dann dient er zur Vorbereitung der AJAX-Abfrage
	if ($ajaxcall !== true) {
		// Daher bauen wir an dieser Stelle das Ziel-DIV zusammen, in das wir dann beim zweiten Lauf die Daten reinladen wollen.
		// (Sonst h�tten wir ja das DIV doppelt)
		// Die Mindesth�he von 235px ist aus dem gerenderten Ergebnis meines (!) Tempplates entnommen, nachdem das komplette DOM nach dem
		// fertigen AJAX-Request gerendert wurde. Damit der Seiteninhalt nicht herumspringt.
		?>
		<div class="<?php echo $wrapclassname; ?>" style="min-height: 235px;">Lade Kalenderdaten...</div>
		<?php
	
	// wenn der Aufruf jedoch per AJAX kam, ganz normal Content ausgeben, mit dem wir unseren DIV f�llen wollen
	} else {
		foreach($events as $event) { /* @var $event SN_Model_Event */
		?>
			<div>
				<strong><?php echo date('d.n.Y', $event->Start); ?> <?php echo gmdate('G:i', $event->Start); ?> <?php echo $event->Location; ?></strong><br />
				<?php
				if (trim($event->URL)=="") {
					echo $event->Title;
					}
				else { ?>
					<a href="<?php echo $event->URL; ?>"><?php echo $event->Title; ?></a>
				<?php } ?>
			</div>
			
			<br />
		<?php
		}
	}
?>