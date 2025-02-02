<table>
    <thead>
        <tr>
            <th>#</th>
            <th>DATE</th>
            <th>DESCRIPTION</th>
            <th>ACTION</th>
            <th>CREATED INFO</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($result as $item)
        <tr>
            <td>{{ $loop->iteration ?? null }}</td>
            <td>
                <span class="audit-trail-block">{{ $item->created_at->diffForHumans() }}</span>
                {{ $item->created_at ?? null }}
            </td>
            <td>
                @if ($item->type == config('audit-trail.events.created'))
                    <span class="text-green">{{ $item->type }}</span>
                @elseif ($item->type == config('audit-trail.events.updated'))
                    <span class="text-blue">{{ $item->type }}</span>
                @elseif ($item->type == config('audit-trail.events.deleted'))
                    <span class="text-red">{{ $item->type }}</span>
                @elseif ($item->type == config('audit-trail.events.retrieved'))
                    <span class="text-purple">{{ $item->type }}</span>
                @elseif ($item->type == config('audit-trail.events.loggedin'))
                    <span class="text-cyan">{{ $item->type }}</span>
                @elseif ($item->type == config('audit-trail.events.imported'))
                    <span class="text-orange">{{ $item->type }}</span>
                @elseif ($item->type == config('audit-trail.events.exported'))
                    <span class="text-teal">{{ $item->type }}</span>
                @else
                    <span class="text-yellow">{{ $item->type ?? 'UNKNOWN' }}</span>
                @endif
                <span class="audit-trail-block">{{ $item->message ?? null }}</span>
            </td>
            <td>
                <span class="audit-trail-block">ID: {{ $item->model_id ?? null }}</span>
                Model:{{ $item->model_type ?? null }}
            </td>
            <td>
                <span class="audit-trail-block">ID: {{ $item->creator_id ?? null }} {{ $item->creator->name ?? '' }}</span>
                Model: {{ $item->creator_type ?? null }}
            </td>
            <td>
                <button type="button" data-item="{{ $item ?? [] }}" class="audit-trail-modal-button" title="Show Detail">&#128065;</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
