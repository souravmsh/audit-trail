@if (!empty($result->count()))
<div class="text-right margin-20-auto">
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($result->onFirstPage())
            <a href="#" disabled>&laquo;</a>
        @else
            <a href="{{ $result->previousPageUrl() }}">&laquo; Prev</a>
        @endif

        {{-- Pagination Elements --}}
        @if ($result instanceof \Illuminate\Pagination\LengthAwarePaginator)
        @foreach ($result->getUrlRange(1, $result->lastPage()) as $page => $url)
            @if ($page == $result->currentPage())
                <a href="#" class="active">{{ $page }}</a>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach
        @endif

        {{-- Next Page Link --}}
        @if ($result->hasMorePages())
            <a href="{{ $result->nextPageUrl() }}">Next &raquo;</a>
        @else
            <a href="#" disabled>&raquo;</a>
        @endif
    </div>
</div>
@endif