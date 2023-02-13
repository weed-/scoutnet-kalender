<?php
/*
  Plugin Name: Scoutnet Kalender
  Plugin URI: http://www.dpsg-paderborn.de/drin/2012/05/endlich-das-scoutnet-kalender-wordpress-plugin/
  Description: Zeigt Termine und Details aus dem Scoutnet-Kalender in Seiten, Artikeln und einem Widget an.
  Version: 1.3
  Author: Scoutnet und Bj&ouml;rn Stromberg
  Author URI: http://www.scoutnet.de/
  Text Domain: scoutnet_kalender
  License: GPLv2
 */

class ScoutnetKalender {

    public static $VERSION = '0.2.0';
    public static $SNK_DIR;
    public static $SNK_URL;

    public function __construct() {

        // define some statics
        ScoutnetKalender::$SNK_DIR = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'scoutnet-kalender'; //plugin_basename( dirname(__file__) );
        ScoutnetKalender::$SNK_URL = WP_PLUGIN_URL . DIRECTORY_SEPARATOR . 'scoutnet-kalender'; //plugin_basename( dirname(__file__) );
        // hook to actions
        add_action('init', array(&$this, 'init'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_init', array(&$this, 'admin_init'));

        // shortcodes
        add_shortcode('snk', array(&$this, 'inline_kalender'));

        // widget
        add_action('widgets_init', function (){return register_widget("ScoutnetKalenderWidget");});
    }

    public function init() {
        $locale_dir = basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'i18n';
        load_plugin_textdomain('scoutnet_kalender', null, $locale_dir);
    }

    public function admin_init() {
        register_setting('snk-opt', 'scoutnet_kalender_ssid');
        register_setting('snk-opt', 'scoutnet_kalender_proxy');
    }

    public function admin_menu() {
        add_options_page("Scoutnet Kalender", "Scoutnet Kalender", 'activate_plugins', 'scoutnet-kalender/options.php',array(&$this, 'admin_options' ));
    }

    public function admin_options(){
        require(plugin_dir_path(__FILE__).'/options.php');
    }

    public function inline_kalender($attr) {
    	
	$ssid = isset($attr['ssid']) ? $attr['ssid'] : get_option('scoutnet_kalender_ssid');
	$elementcount = isset($attr['elementcount']) ? $attr['elementcount'] : '0';
	$externalTemplateName = isset($attr['externaltemplatename']) ? basename($attr['externaltemplatename']) : ''; // remember: wordpress always returns small case'd values for shortcodes.
	$events = ScoutnetKalender::getSnEvents($ssid, $elementcount);
	$buffer = ob_start();
	
	if (!empty($externalTemplateName) && @is_readable(get_stylesheet_directory().'/scoutnet-kalender_inline_kalender_'.$externalTemplateName.'_list.php')) {
		require(get_stylesheet_directory().'/scoutnet-kalender_inline_kalender_'.$externalTemplateName.'_list.php');
	} else {
		require(plugin_dir_path(__FILE__).'/templates/inline_kalender_list.php');
	}
	
	return ob_get_clean();
    }

    public function activation_hook() {
        
    }

    public static function getSnEvents($ssid, $elementcount = 0) {
        require_once 'lib/scoutnet_webservice/class.tx_shscoutnetwebservice_sn.php';

        $SN = new tx_shscoutnetwebservice_sn();

		if ($elementcount == 0) {
			$filter = array(
				'limit' => '40',
				'after' => 'now()',
			);
		} else {
			$filter = array(
				'limit' => $elementcount,
				'after' => 'now()',
			);
		}
		
		// Use 'explode' instead of 'array' to be able to procees mutliple calenders
        $ids = explode(',', $ssid);

        $events = $SN->get_events_for_global_id_with_filter($ids, $filter);
		return $events;
    }
}

class ScoutnetKalenderWidget extends WP_Widget {
    function __construct() {

        $widget_ops = array('classname' => 'ScoutnetKalenderWidget', 'description' => 'Anzeige von Scoutnet-Kalendern');
        parent::__construct( 'ScoutnetKalenderWidget', 'Scoutnet Kalender', $widget_ops );

        // AJAX actions
        // if both logged in and not logged in users can send this AJAX request,
        // add both of these actions, otherwise add only the appropriate one
        // thanks to http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/#js-global
        add_action( 'wp_ajax_nopriv_SNK_ajax-submit', array(&$this, 'ajax_widget'));
        add_action( 'wp_ajax_SNK_ajax-submit', array(&$this, 'ajax_widget'));
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'ssid' => '3', 'elementcount'=>'0', 'wrapclassname'=>'snk_widget', 'externalTemplateName'=>''));
        $title = $instance['title'];
        $ssid = $instance['ssid'];
        $elementcount = $instance['elementcount'];
        $wrapclassname = $instance['wrapclassname'];
        $externalTemplateName = $instance['externalTemplateName'];
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Titel: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label><br />
            <label for="<?php echo $this->get_field_id('ssid'); ?>">Kalender-ID: <input class="widefat snk_ssid_chooser" id="<?php echo $this->get_field_id('ssid'); ?>" name="<?php echo $this->get_field_name('ssid'); ?>" type="text" value="<?php echo esc_attr($ssid); ?>" /></label><br />
            <label for="<?php echo $this->get_field_id('elementcount'); ?>">Element-Anzahl (0=alle): <input class="widefat snk_elementcount_chooser" id="<?php echo $this->get_field_id('elementcount'); ?>" name="<?php echo $this->get_field_name('elementcount'); ?>" type="text" value="<?php echo esc_attr($elementcount); ?>" /></label><br />
            <label for="<?php echo $this->get_field_id('wrapclassname'); ?>">Name der &auml;u&szlig;eren CSS-Klasse: <input class="widefat snk_wrapclassname_chooser" id="<?php echo $this->get_field_id('wrapclassname'); ?>" name="<?php echo $this->get_field_name('wrapclassname'); ?>" type="text" value="<?php echo esc_attr($wrapclassname); ?>" /></label><br />
            <label for="<?php echo $this->get_field_id('externalTemplateName'); ?>" title="Zusammengesetzt: TEMPLATEPATH/scoutnet-kalender_widget_kalender_%EINGABE%_event.php UND TEMPLATEPATH/scoutnet-kalender_widget_kalender_%EINGABE%_list.php">Name des Templates:</br /><input class="widefat snk_externalTemplateName_chooser" id="<?php echo $this->get_field_id('externalTemplateName'); ?>" name="<?php echo $this->get_field_name('externalTemplateName'); ?>" type="text" value="<?php echo esc_attr($externalTemplateName); ?>" /></label>
        </p>
<?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['ssid'] = (empty($new_instance['ssid']) || !preg_match('/^[0-9]+$/', $new_instance['ssid'])) ? '3' : $new_instance['ssid'];
        $instance['elementcount'] = (empty($new_instance['elementcount']) || !preg_match('/^[0-9]+$/', $new_instance['elementcount'])) ? '0' : $new_instance['elementcount'];
        $instance['wrapclassname'] = empty($new_instance['wrapclassname']) ? 'snk_widget' : $new_instance['wrapclassname'];
        $instance['externalTemplateName'] = $new_instance['externalTemplateName'];
        return $instance;
    }

    function widget($args, $instance, $ajaxcall = false) {
    	// Variablen verarbeiten
        extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$ssid = (empty($instance['ssid']) || !preg_match('/^[0-9]+$/', $instance['ssid'])) ? '3' : $instance['ssid'];
		$elementcount = (empty($instance['elementcount']) || !preg_match('/^[0-9]+$/', $instance['elementcount'])) ? '0' : $instance['elementcount'];
		$wrapclassname = empty($instance['wrapclassname']) ? 'snk_widget' : htmlentities($instance['wrapclassname']);
		$externalTemplateName = basename($instance['externalTemplateName']);
		
		// einen Teil der Magie brauchen wir erst beim AJAX-Abruf
		if ($ajaxcall === true) {
	        // die Events holen wir erst beim AJAX-Request, weil wir genau diese
	        // Zeit beim initialen Seitenaufruf und DOM-Rendering einsparen wollen
        	$events = ScoutnetKalender::getSnEvents($ssid, $elementcount);
        	
        // manch anderes ben�tigen wir ausschlie�lich zur Vorbereitung des AJAX-Abrufs
	    } else {
	    	// Widget-Einleitung (<li> und <h2> und co)
			echo $before_widget;			
			if ($title != ' ') {
				echo $before_title . $title . $after_title;
			}
	        
			// Wordpress anweisen unsere JS-File ins Dokument zu laden (scoutnet_kalender_ajax.js)
			wp_enqueue_script( 'snk-ajax-request', plugin_dir_url( __FILE__ ) . 'scoutnet_kalender_ajax.js', array( 'jquery' ) );
			 
			// f�r den AJAX-Request brauchen wir eine URL (wp-admin/admin-ajax.php) und alle der Widget-Funktion �bergebenen Daten (wir encodieren das hier der Einfachheit halber in JSON)
			wp_localize_script( 'snk-ajax-request', 'SNK_ajax',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wrapclassname' => $wrapclassname,
					'args' => (is_array($args) ? json_encode($args) : array()),
					'data' => json_encode($instance),
					'nonce' => wp_create_nonce('snk-call-nonce')
				)
			);
    	}    

		// je nachdem ob ein externes Template genutzt werden soll, wird dieses statt dem normalen eingebunden
		// vorausgesetzt die Datei existiert und ist lesbar
		// Ein externes Template ist in diesem Kontext ein Template das sich innerhalb des gerade genutzten Themes befindet,
		// statt im Plugin-Templates-Ordner
        if (!empty($externalTemplateName) && @is_readable(get_stylesheet_directory().'/scoutnet-kalender_widget_kalender_'.$externalTemplateName.'_list.php')) {
        	/* externes Template list.php */
        	require (get_stylesheet_directory().'/scoutnet-kalender_widget_kalender_'.$externalTemplateName.'_list.php');
        } else {
        	/* internes Template list.php */
        	require (plugin_dir_path(__FILE__).'templates/widget_kalender_list.php');
        }
        
        // einen Teil der Magie brauchen wir nur beim beim den AJAX vorbereitenden Abruf
        if ($ajaxcall !== true) {
        	// Widget-Ausleitung (</li> und co)
        	echo $after_widget;
        }
    }
	
	function ajax_widget() {
		// zur Sicherheit (best practice) haben wir eine einmalige "nonce" generiert, die nur f�r diesen einen Abruf g�ltig ist
		// das sorgt daf�r, dass niemand unsere AJAX-Schnittstelle f�r sich selbst benutzen kann
		if (!wp_verify_nonce( $_POST['snk_nonce'], 'snk-call-nonce' )) {
			die('Fehler beim Abruf!');
		}
		
		// beim Aufruf aus JS haben wir die Variablen snk_args und snk_data
		// damit k�nnen wir einfach die �bliche Widget-Funktion ansteuern und fertig
		$args = json_decode(stripslashes($_POST['snk_args']), true);
		$instance = json_decode(stripslashes($_POST['snk_data']), true);
				
		$this->widget($args, $instance, true);
		// exit muss sein, damit der AJAX-Call funktioniert
		exit;
	}
}

$snk = new ScoutnetKalender();
register_activation_hook(__FILE__, array(&$snk, 'activation_hook'));
?>
