<?php

namespace Reverb\Lib;

interface DbConnectionAwareInterface
{
    public function GetDbConnection();
    public function SetDbConnection(DbConnection $instance);
}
