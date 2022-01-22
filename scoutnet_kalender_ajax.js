// Man könnte den Aufruf auch per DOM-ready machen, davon ausgehend,
// dass Wordpress unser JS jedoch ans Ende des Dokumentes packt, ist das DOM
// schon fertig, wenn dieses Skript geladen und ausgeführt wird
//
//jQuery(document).ready(function()
//{
	// nur AJAX-Request ausführen, wenn das Zieldiv im DOM vorhanden ist
	// da der Name der Klasse in der Adminoberfläche beliebig einstellbar ist,
	// müssen wir den hier natürlich kennen
	// kommt aus SNK_ajax.wrapclassname
	// wir verwenden eine Klasse statt einer ID, da wir den Content so auch in mehrere
	// Elemente reinladen könnten (wenn das gewünscht ist)
	if(jQuery('.' + SNK_ajax.wrapclassname).length >= 1) {
		jQuery.post(
			SNK_ajax.ajaxurl,
			{
				action : 'SNK_ajax-submit',
				snk_args : SNK_ajax.args,
				snk_data : SNK_ajax.data,
				snk_nonce: SNK_ajax.nonce
			},
			function(daten) {
				// wenn Daten rauskommen
				if(daten.length > 0 && daten != '-1') {
					// abgefragte Daten unverändert ins DOM einfügen
					jQuery('.' + SNK_ajax.wrapclassname).html(daten);
				} else {
					// ansonsten tun wir sicherheitshalber mal nix und returnen
					return false;
				}
			}
		);
	}
//});