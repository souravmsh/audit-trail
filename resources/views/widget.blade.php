<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var link = "{{ asset('vendor/audit-trail/stylesheet.css') }}";
    var auditTrailStyle = document.querySelector('link[href="' + link + '"]');
    if (!auditTrailStyle) {
        var newLink = document.createElement('link');
        newLink.rel = 'stylesheet';
        newLink.href = link;
        document.head.appendChild(newLink);
    }
});
</script>

<div class="audit-trail-container">
    <div class="audit-trail-header">
        <h1 class="margin-bottom-0">{{ $title ?? 'Audit Trail History' }}</h1>
        <p class="audit-trail-count margin-bottom-0">
            @if ($result instanceof \Illuminate\Pagination\LengthAwarePaginator)
                showing {{ $result->firstItem()??0 }} to {{ $result->lastItem()??0 }} of total {{ $result->total()??0 }} entries
            @else
                showing limited to {{ $result->count() ?? 0 }} entries
            @endif
        </p>
    </div>
    <div class="audit-trail-body">
        @if(collect($result)->isEmpty())
            <p class="audit-trail-no-data">No audit trail found.</p>
        @else
            <div class="audit-trail-table-wrapper">
                @include('audit-trail::table')
                @include('audit-trail::pagination')
                @include('audit-trail::modal')
            </div>
        @endif
    </div>
</div>


