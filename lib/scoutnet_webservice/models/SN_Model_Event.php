<?php

/**
 * SN_Model_Event
 * @property integer $ID
 * @property integer $UID
 * @property integer $SSID
 * @property string $Title
 * @property string $Organizer
 * @property string $Target_Group
 * @property integer $Start timestamp
 * @property integer $End timestamp
 * @property boolean $All_Day
 * @property string $ZIP PLZ
 * @property string $Location Ort
 * @property string $URL_Text Linktext
 * @property string $URL Link
 * @property string $Description Beschreibung
 * @property array $Stufen
 * @property array $Keywords z. B. array(10 => 'Schulung/Kurs', 6 => 'Vorstände')
 * @property SN_Model_Kalender $Kalender
 * @property string $Last_Modified_By
 * @property integer $Last_Modified_At timestamp
 * @property string $Created_By
 * @property integer $Created_At timestamp
 * @property SN_Model_User $Author 
 */
class SN_Model_Event extends ArrayObject {

    function __construct($array) {
        parent::__construct($array);
    }

    public function __get($name) {
        return $this[$name];
    }

    public function get_Author_name() {
        if (isset($this['Author']) && $this['Author'] != null) {
            return (string) htmlentities(utf8_decode($this['Author']->get_full_Name()));
        }

        return (string) "";
    }

    public function get_Stufen_Images() {
        if (isset($this['Stufen']) && $this['Stufen'] != null) {

            $stufen = "";
            foreach ($this['Stufen'] as $stufe) {
                $stufen .= $stufe->get_Image_URL();
            }

            return (string) $stufen;
        }
        return (string) "";
    }

}
