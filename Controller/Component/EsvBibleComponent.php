<?php
class EsvBibleComponent extends Component {
    var $settings = array();
    var $key = null;

    function initialize(Controller $controller, $settings=array()) {
    }

    function set_settings($settings) {
        $this->settings = $settings;
    }

    function get_key() {
        if ($this->key == null) {
            $this->key = isset($settings["key"]) ? $settings["key"] : "IP";
        }

        return $this->key;
    }

    function get_passage($passage) {
        $passage = urlencode($passage);
        $options = "include-passage-references=false&audio-format=flash";
        $url = "http://www.esvapi.org/v2/rest/passageQuery?key=" . 
                $this->get_key() . "&passage=$passage&$options";

        CakeLog::write(LOG_DEBUG, "getting passage from: $url");

        $data = @fopen($url, "r") ;

        $passage_str = null;

        if ($data) {
            while (!feof($data)) { 
                $buffer = fgets($data, 4096);
                $passage_str .= $buffer;
            }
            fclose($data);
        } else {
            CakeLog::write("error", "fopen failed for url to webservice: $url");
        }

        return $passage_str;
    }
}
