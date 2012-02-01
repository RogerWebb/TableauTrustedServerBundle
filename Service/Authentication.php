<?php

namespace Tableau\Bundle\TableauTrustedServerBundle\Service;

class Authentication {

    private $protocol;
    private $host;
    private $port;

    public function __construct($host, $port = 8000, $protocol = 'http') {
        $this->setHost($host);
        $this->setProtocol($protocol);
        $this->setPort($port);
    }

    public function setProtocol($protocol) { $this->protocol = $protocol; }

    public function getProtocol() { return $this->protocol; }

    public function setHost($host) {
        $fragments = parse_url($host);

        if(array_key_exists('path', $fragments)) {
            $this->host = $fragments['path'];
            $this->setProtocol('http');
        }

        if(array_key_exists('host', $fragments)) {
            $this->host = $fragments['host'];
        }

        if(array_key_exists('scheme', $fragments)) {
            $this->setProtocol($fragments['scheme']);
        }

        if(array_key_exists('port', $fragments)) {
            $this->setPort($fragments['port']);
        }
    }

    public function getHost() { return $this->host; }

    public function setPort($port) { $this->port = strval($port); }

    public function getPort() { return $this->port; }

    public function setUniqueId($unique_id) { $this->unique_id = $unique_id; }

    public function getUniqueId() { return $this->unique_id; }

    public function getUniqueIdFor($username) {
        $ch = curl_init();

        $post_fields = array(
            'username' => $username
        );

        curl_setopt($ch, CURLOPT_URL, $this->getProtocol() . '://' . $this->getHost() . ':' . $this->getPort() . '/trusted');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

        $data = curl_exec($ch);

        curl_close($ch);

        $this->setUniqueId($data);

        return $data;
    }

    public function constructViewUrl($workbook, $view, $unique_id) {
        if($unique_id == null)
            $unique_id = $this->getUniqueId();

        return $this->getProtocol() . "://" . $this->getHost() . ':' . $this->getPort() . "/trusted/$unique_id/views/$workbook/$view";
    }
}

