<?php

/**
 * SN_Model_User
 * @property string $userid
 * @property string $firstname
 * @property string $surname
 * @property string $sex
 * @property string $Surname
 * @property string $User_ID
 * @property string $id
 * @property string $Firstname
 * @property string $Lastname
 * @property string $lastname
 */
class SN_Model_User extends ArrayObject {

    function __construct($array) {
        if (isset($array['id'])) {
            $array['userid'] = $array['id'];
        } elseif (isset($array['User_ID'])) {
            $array['userid'] = $array['User_ID'];
        } elseif (empty($array['userid'])) {
            throw new Exception("not a valid user record: " . print_r($array, true));
        }
        if (isset($array['Firstname'])) {
            $array['firstname'] = $array['Firstname'];
        }
        if (array_key_exists('surname', $array)) {
            $array['Surname'] = $array['surname'];
        } elseif (array_key_exists('lastname', $array)) {
            $array['Surname'] = $array['lastname'];
        } elseif (array_key_exists('Lastname', $array)) {
            $array['Surname'] = $array['Lastname'];
        }

        $array['User_ID'] = $array['userid'];
        $array['id'] = $array['userid'];

        $array['Firstname'] = $array['firstname'];

        $array['surname'] = $array['Surname'];
        $array['Lastname'] = $array['Surname'];
        $array['lastname'] = $array['Surname'];
        parent::__construct($array);
    }

    public function __get($name) {
        return $this[$name];
    }

    /**
     * @deprecated
     */
    public function readable_name() {
        return $this->get_long_name();
    }

    public function get_full_name() {
        $full_name = $this['Firstname'] . ' ' . $this['Surname'];
        return trim($full_name) ? trim($full_name) : $this['userid'];
    }

    public function get_firstname() {
        return $this->get_first_name();
    }

    public function get_first_name() {
        return trim($this['Firstname']) ? trim($this['Firstname']) : $this['userid'];
    }

    public function get_fullname() {
        return $this->get_full_name();
    }

    public function get_long_name() {
        $full_name = $this->get_full_name();
        if ($full_name) {
            return $full_name . ' (' . $this['id'] . ')';
        } else {
            return $this['id'];
        }
    }

    /**
     * @return SN_Model_Structure
     */
    public function get_structure() {
        return SN_DataAccess_Structure::instance()->get($this['structureid']);
    }

    public function dear() {
        if (isset($this['sex']) && $this['sex'] === 'f') {
            return "Liebe";
        } else {
            return "Lieber";
        }
    }

    public function grammar($he, $she) {
        if (isset($this['sex']) && $this['sex'] === 'f') {
            return $he;
        } else {
            return $she;
        }
    }

    public function get_contact_url_short() {
        return SN::instance()->get_users_contact_url_short($this);
    }

    public function get_contact_url() {
        return SN::instance()->get_users_contact_url($this);
    }

    public function get_contact_link_tag_full_name() {
        return SN::instance()->get_users_contact_link_tag_full_name($this);
    }

    public function get_contact_link_tag_first_name() {
        return SN::instance()->get_users_contact_link_tag_first_name($this);
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_User.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sh_scoutnet_webservice/sn/models/SN_Model_User.php']);
}
