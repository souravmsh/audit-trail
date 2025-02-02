<?php

namespace Souravmsh\AuditTrail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditTrail extends Model
{
    public $table;
    protected $guarded = []; 

    public function __construct()
    {
        $this->table = config('audit-trail.migration.table');
    }

    public function creator(): MorphTo
    {
        return $this->morphTo();
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }


}