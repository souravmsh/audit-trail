<?php

namespace Souravmsh\AuditTrail\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use InvalidArgumentException;

class AuditTrailService
{
    public function history(object|array $request = []): LengthAwarePaginator | Paginator
    {
        $request = (object) $request;
    
        $query = app(config('audit-trail.model'))->query();

        if (!empty($request->showModel)) {
            $query->with('model');
        }            
        if (!empty($request->showCreator)) {
            $query->with('creator');
        }

        if (!empty($request->message)) {
            $query->where('message', 'like', '%' . $request->message . '%');
        }

        if (!empty($request->model_type)) {
            $query->where('model_type', $request->model_type);
        }

        if (!empty($request->model_id)) {
            $query->where('model_id', $request->model_id);
        }

        if (!empty($request->creator_type)) {
            $query->where('creator_type', $request->creator_type);
        }

        if (!empty($request->creator_id)) {
            $query->where('creator_id', $request->creator_id);
        }

        $query->orderBy('id', 'desc');

        if (!empty($request->limit)) {
            return $query->limit($request->limit)
                ->simplePaginate($request->per_page ?? config('audit-trail.migration.pagination'));
        }

        return $query->paginate($request->per_page ?? config('audit-trail.migration.pagination'));
    }
    
    public function log(array|string $type, string $message = null, object $model = null, int $modelId = null, array|object|null $data = []): ?object
    {
        // If first parameter is an array, extract values
        if (is_array($type) || is_object($type)) {
            $request = (array) $type;
            $type     = $request['type'] ?? null;
            $message  = $request['message'] ?? '';
            $model    = $request['model_type'] ?? null;
            $modelId  = $request['model_id'] ?? null;
            $data     = $request['data'] ?? [];
        }
        
        if (!in_array($type, config('audit-trail.migration.type') ?? [], true)) {
            throw new InvalidArgumentException('Invalid type provided.');
        }
        
        if (!empty($model) && (!method_exists($model, 'getTable'))) {
            throw new InvalidArgumentException('Invalid model provided.');
        }
        
        if (!empty($modelId) && !is_numeric($modelId)) {
            throw new InvalidArgumentException('Invalid model id provided.');
        }

        return $this->saveHistory($type, $model, $data, $message, $modelId);
    }

    public function saveHistory(string $type, string|object|null $model = null, ?array $data = null, ?string $message = null, ?int $modelId = null): ?object
    {
        if (empty($data) && empty($message)) {
            return null;
        }
    
        $creator   = auth()->user();
        $userName  = $creator->name ?? 'Unknown User';
    
        // Determine model class & table
        if (is_object($model)) {
            $modelType = get_class($model);
            $tableName = method_exists($model, 'getTable') ? $model->getTable() : null;
            $modelId   = $modelId ?? $model->id ?? null;
        } elseif (is_string($model) && class_exists($model)) {
            $modelType = $model;
            $tableName = app($model)->getTable();
        } else {
            $modelType = null;
            $tableName = null;
        }
    
        // Extract first three fields for message
        $fields = !empty($data) 
            ? str_replace("_id", "", implode(", ", array_slice(array_keys((array) $data), 0, 3))) . (count($data) > 3 ? ' etc.' : '')
            : 'No fields';
        
        // Default message if not provided
        $message = $message ?? "{$userName} {$type} the fields {$fields} of a record with ID {$modelId} in the {$tableName} table.";
    
        return app(config('audit-trail.model'))::create([
            'type'         => $type,
            'message'      => $message,
            'model_type'   => $modelType,
            'model_id'     => $modelId,
            'creator_type' => $creator ? get_class($creator) : null,
            'creator_id'   => $creator?->id,
            'data'         => $data ? json_encode($data) : null,
            'agent'        => json_encode($this->getUserAgent()),
            'batch_id'     => request()->input('batch_id', uniqid()),
            'status'       => config('audit-trail.migration.status.unseen'),
        ]);
    }
    

    private function getUserAgent(): array
    {
        return [
            'ip'         => request()->ip(),
            'user-agent' => request()->userAgent(),
            'url'        => request()->fullUrl(),
        ];
    }
}