<?php
class Fetcher
{
    private $_dom;
    
    public function fetch()
    {
        require_once __DIR__.'/Config.php';
        extract($config);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $hnurl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

        try {
            $buffer = curl_exec($ch);

            curl_close($ch);

            if (isset($buffer) && filter_var($buffer, FILTER_SANITIZE_URL))
                $urls = Array();

            $this->_dom = new DOMDocument();
            @$this->_dom->loadHTML($buffer);
            $this->_dom->formatOutput = true;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getDom()
    {
        return $this->_dom;
    }
}
