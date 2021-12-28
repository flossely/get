<?php

class Package
{
    public function info($id, $key)
    {
        $pkgOpen = file_get_contents($id.'.pkg');
        $pkgExp = explode('=|1|=', $pkgOpen);
        $pkgHead = $pkgExp[0];
        $pkgProp = explode('=|2|=', $pkgHead);
        return $pkgProp;
    }
    
    public function files($id)
    {
        $pkgOpen = file_get_contents($id.'.pkg');
        $pkgExp = explode('=|1|=', $pkgOpen);
        $pkgBody = $pkgExp[1];
        return $pkgBody;
    }
}

class System
{
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function info()
    {
        return $this->id;
    }
}

class Source
{
    public function __construct($host)
    {
        $this->host = $host;
    }
    
    public function info()
    {
        return $this->host;
    }
}

$system = new System(file_get_contents('system.info'));
$source = new Source(file_get_contents('get.cfg'));
$package = new Package;
