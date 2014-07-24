<?php

namespace Reverb\Lib;

use \Zend\Db\Adapter\Adapter;

interface DbAdapterAwareInterface
{
    public function GetDbAdapter();
    public function SetDbAdapter(Adapter $instance);
}
