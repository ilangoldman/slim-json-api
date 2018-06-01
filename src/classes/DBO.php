<?php

abstract class DBO {
    protected $db;
    public function __construct($db) {
        $this->db = $db;
    }
}