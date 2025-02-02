<?php

namespace Souravmsh\AuditTrail\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Souravmsh\AuditTrail\Services\AuditTrailService
 */
class AuditTrail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Souravmsh\AuditTrail\Services\AuditTrailService::class;
    }
}
