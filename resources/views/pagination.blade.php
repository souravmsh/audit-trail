@if ($result->count())
    <div class="text-right margin-20-auto">
        <div class="pagination">
            {{-- Previous Page Link --}}
            @if ($result->onFirstPage())
                <a href="#" disabled>&laquo;</a>
            @else
                <a href="{{ $result->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}">&laquo; Prev</a>
            @endif

            {{-- Pagination Elements --}}
            @if ($result instanceof \Illuminate\Pagination\LengthAwarePaginator)
                @php
                    $currentPage = $result->currentPage();
                    $lastPage = $result->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                {{-- First Page Link --}}
                @if ($start > 1)
                    <a href="{{ $result->url(1) . '&' . http_build_query(request()->except('page')) }}">1</a>
                @endif

                {{-- Page Links --}}
                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $currentPage)
                        <a href="#" class="active">{{ $page }}</a>
                    @else
                        <a href="{{ $result->url($page) . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Last Page Link --}}
                @if ($end < $lastPage)
                    <a href="{{ $result->url($lastPage) . '&' . http_build_query(request()->except('page')) }}">{{ $lastPage }}</a>
                @endif
            @endif

            {{-- Next Page Link --}}
            @if ($result->hasMorePages())
                <a href="{{ $result->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}">Next &raquo;</a>
            @else
                <a href="#" disabled>&raquo;</a>
            @endif
        </div>
    </div>
@endif
