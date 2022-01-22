<?php
class SN_Model_Stufe extends ArrayObject{
	function __construct( $array ){
		parent::__construct($array);
	}


	public function get_Image_URL(){
		if (isset($this['id'])) {
			return (string) "<img src='http://kalender.scoutnet.de/2.0/images/".$this['id'].".gif' alt='".htmlentities($this['bezeichnung'])."' />";
		} 

		return (string) "";
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_Stufe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_Stufe.php']);
}
