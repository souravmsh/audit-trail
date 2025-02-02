<?php

namespace Souravmsh\AuditTrail\View\Components;

use Illuminate\View\Component;
use Souravmsh\AuditTrail\Facades\AuditTrail;

class Widget extends Component
{
    public string $title;
    public ?int $perPage;
    public ?int $limit;
    public ?string $type;
    public ?string $modelType;
    public ?string $modelId;
    public ?string $creatorType;
    public ?string $creatorId;
    public ?string $dateFrom;
    public ?string $dateTo;
    public bool $showModel;
    public bool $showCreator;
    public object|array|null $result;

    /**
     * Create a new component instance.
     *
     * @param string|null $title
     * @param int|null $limit
     * @param string|null $type
     * @param string|null $modelType
     * @param string|null $modelId
     * @param string|null $creatorType
     * @param string|null $creatorId
     * @param bool $showModel
     * @param bool $showCreator
     */
    public function __construct(
        string $title = null,
        int $perPage  = 10,
        int $limit    = null,
        string $type  = null,
        string $modelType = null,
        string $modelId = null,
        string $creatorType = null,
        string $creatorId = null,
        string $dateFrom = null,
        string $dateTo   = null,
        bool $showModel = false,
        bool $showCreator = false
    ) {
        $this->title       = $title ?? 'Audit Trail';
        $this->perPage     = $perPage;
        $this->limit       = $limit;
        $this->type        = $type;
        $this->modelType   = $modelType;
        $this->modelId     = $modelId;
        $this->creatorType = $creatorType;
        $this->creatorId   = $creatorId;
        $this->dateFrom    = $dateFrom;
        $this->dateTo      = $dateTo;
        $this->showModel   = filter_var($showModel, FILTER_VALIDATE_BOOLEAN);
        $this->showCreator = filter_var($showCreator, FILTER_VALIDATE_BOOLEAN);

        // Fetch audit trail history
        $this->result = AuditTrail::history([
            'per_page'      => $this->perPage,
            'limit'         => $this->limit,
            'show_model'    => $this->showModel,
            'show_creator'  => $this->showCreator,
            'type'          => $this->type,
            'model_type'    => $this->modelType,
            'model_id'      => $this->modelId,
            'creator_type'  => $this->creatorType,
            'creator_id'    => $this->creatorId,
            'date_from'     => $this->dateFrom,
            'date_to'       => $this->dateTo,
        ]);

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('audit-trail::widget', [
            'title'       => $this->title,
            'result'      => $this->result,
            'showModel'   => $this->showModel,
            'showCreator' => $this->showCreator,
        ]);
    }
}
