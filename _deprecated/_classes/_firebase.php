<?php
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;


class Firebase {

    
    private $firebase;

    public function __construct($log) {
        $path = __DIR__.'\firebase\firebase-credentials.json';
        $serviceAccount = ServiceAccount::fromJsonFile($path);
        // $serviceAccount = ServiceAccount::discover();
        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        // $log->addInfo($this->$firebase);
        
    }

    public function auth() {
        return $this->firebase->getAuth();
    }

    public function getUser($id) {
        $auth = $this->auth();
        return $auth->getUser($id);
    }

}

