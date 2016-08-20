<?php

namespace Blanfordia\Visitors\Support;

use PragmaRX\Support\Migration as PragmaRxMigration;

abstract class Migration extends PragmaRxMigration
{
    protected function checkConnection()
    {
        $this->manager = app()->make('db');

        $this->connection = $this->manager->connection('visitors');

        parent::checkConnection();
    }
}
