<?php

namespace App\Exceptions\Aerticket;

use RuntimeException;

class AerticketNotConfiguredException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('AERTiCKET integration is not configured. Set AERTICKET_ENABLED and API credentials in .env.');
    }
}
