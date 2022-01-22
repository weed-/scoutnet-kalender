<?php

// Deutsche Zeit
date_default_timezone_set('Europe/Berlin');

	// Wenn der Aufruf (noch) nicht per AJAX kam, dann dient er zur Vorbereitung der AJAX-Abfrage
	if ($ajaxcall !== true) {
		// Daher bauen wir an dieser Stelle das Ziel-DIV zusammen, in das wir dann beim zweiten Lauf die Daten reinladen wollen.
		// (Sonst hätten wir ja das DIV doppelt)
		// Die Mindesthöhe von 251px ist aus dem gerenderten Ergebnis entnommen, nachdem das komplette DOM nach dem
		// fertigen AJAX-Request gerendert wurde. Damit der Seiteninhalt nicht herumspringt.
		?>
		<div class="<?php echo $wrapclassname; ?>" style="min-height: 251px;">Lade Scoutnet-Daten ...</div>
		<?php
	
	// Wenn dieser Aufruf jetzt via AJAX kam, wir dieser Content ausgeben der das DIV füllt.
	} else {
		setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
		foreach($events as $event) { /* @var $event SN_Model_Event */
		?>
			<div>
				<div class="date-container">
					<span class="day"><?php echo date('d', $event->Start); ?></span>
					<span class="month"><?php echo htmlentities(strftime('%b', $event->Start)); ?></span>
				</div>
				<div class="info-container">
					<span class="event-title">
						<?php if (trim($event->URL) == '') {
							echo $event->Title;
							}
						else {
							?>
							<a href="<?php echo addslashes(trim($event->URL)); ?>"><?php echo trim($event->Title); ?></a>
							<?php
						}
						?>
					</span>
					<span class="event-descr">
						<?php echo date('G:i', $event->Start); ?>
						<?php echo $event->Location; ?>
					</span>
				</div>
				<div style="clear: both;"></div>
			</div>
		<?php
		}
	}
?>