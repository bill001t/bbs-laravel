<?php

namespace App\Services\config\bm;

use App\Services\Api\config\ds\relation\WindidConfig;

class WindidStoreService
{

    public function getStore()
    {
        $ds = app(WindidConfig::class);
        $stores = $ds->getValues('storage');
        $config = $ds->getValues('attachment');
        $config = $config['storage.type'];
        if (!$config || !isset($stores[$config])) {
            $cls = 'WINDID:library.storage.WindidStorageLocal';
        } else {
            $store = unserialize($stores[$config]);
            $cls = $store['components']['path'];
        }
        $srv = Wind::import($cls);
        return new $srv();
        //$this->store = Wind::getComponent($this->bhv->isLocal ? 'windidLocalStorage' : 'windidStorage');
    }

    public function setStore($key, $storage)
    {
        $config = new WindidConfigSet('storage');
        $config->set($key, serialize($storage))->flush();
        return true;
    }
}

?>