<?php

 /**
  * SN_Model_Kalender
  * @property integer $ID z.B. 3
  * @property string $Ebene z.B. DPSG
  * @property string $Name z. B. Deutsche Pfadfinderschaft Sankt Georg (DPSG)
  * @property string $Verband z. B. DPSG
  * @property string $Ident z. B. DPSG
  * @property integer $Ebene_Id z. B. 5
  */

class SN_Model_Kalender extends ArrayObject {

    function __construct($array) {
        parent::__construct($array);
    }

    public function __get($name) {
        return $this[$name];
    }

    public function get_long_Name() {
        return (string) htmlentities(utf8_decode($this['Ebene'])) . (($this['Ebene_Id'] >= 7) ? "<br>" . htmlentities(utf8_decode($this['Name'])) : "");
    }

    public function get_Name() {
        return (string) htmlentities(utf8_decode($this['Ebene'])) . (($this['Ebene_Id'] >= 7) ? "&nbsp;" . htmlentities(utf8_decode($this['Name'])) : "");
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_Kalender.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_Kalender.php']);
}
