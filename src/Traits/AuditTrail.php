<?php

namespace Souravmsh\AuditTrail\Traits;

trait AuditTrail
{
    protected static function bootAuditTrail()
    {
        foreach (config('audit-trail.events') as $event) {
            static::$event(function ($model) use ($event) {
                self::handleAuditEvent($event, $model);
            });
        }
    }

    protected static function handleAuditEvent($event, $model)
    {
        $data = [];

        if ($event === config('audit-trail.events.updated')) {
            $data = self::getChangedAttributes($model);
        } elseif ($event === config('audit-trail.events.created')) {
            $rawData = collect($model->getAttributes())->except(self::getIgnoredAttributes($model))->toArray();
            $data = collect($rawData)->mapWithKeys(function ($value, $key) {
                return [$key => ['new' => $value]];
            })->toArray();
        }

        $filteredData = self::filterAllowedAttributes($model, $data);
        
        if (!empty($filteredData) || $event === config('audit-trail.events.deleted')) {
            \Souravmsh\AuditTrail\Facades\AuditTrail::saveHistory(strtoupper($event), $model, $filteredData);
        }
    }

    protected static function getChangedAttributes($model)
    {
        $oldAttributes = $model->getOriginal();
        $newAttributes = $model->getAttributes();
        $changedData = [];

        foreach ($newAttributes as $key => $value) {
            if (array_key_exists($key, $oldAttributes) && !in_array($key, self::getIgnoredAttributes($model))) {
                $oldValue = $oldAttributes[$key];

                if ((is_numeric($oldValue) && is_numeric($value) && round($oldValue, 8) !== round($value, 8)) || $oldValue !== $value) {
                    $changedData[$key] = ['old' => $oldValue, 'new' => $value];
                }
            }
        }

        return self::filterAllowedAttributes($model, $changedData);
    }

    protected static function filterAllowedAttributes($model, $attributes)
    {
        $allowed = property_exists($model, 'auditTrailAllowedAttributes') && !empty($model->auditTrailAllowedAttributes)
            ? $model->auditTrailAllowedAttributes
            : null;

        return $allowed ? array_intersect_key($attributes, array_flip($allowed)) : $attributes;
    }

    protected static function getIgnoredAttributes($model)
    {
        $ignored = property_exists($model, 'auditTrailIgnoreAttributes') && !empty($model->auditTrailIgnoreAttributes)
            ? $model->auditTrailIgnoreAttributes
            : [];

        return array_merge(config('audit-trail.migration.ignored_attributes'), $ignored);
    }
}
