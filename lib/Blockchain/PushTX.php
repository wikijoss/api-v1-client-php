<?php

class Push {
    public function __construct(Blockchain $blockchain) {
        $this->blockchain = $blockchain;
    }

    public function TX($hex) {
        var_dump($this->blockchain->post('pushtx', array('tx'=>$hex)));

        return true;
    }
}