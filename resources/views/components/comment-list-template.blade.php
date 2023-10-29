<div class="card-header">
    <b>Comments</b>
    <br>
    <br>

    @if (!empty($comments->toArray()))
        @foreach ($comments as $comment)
            <div class="card bg-white">
                <div class="card-header">
                    <b>{{ $comment->user->name }}</b>
                </div>
                <div class="card-body">
                    {!! $comment->content !!}
                </div>
                <div class="card-footer text-muted">
                    {{ $comment->created_at->format('M d Y h:i:s A') }}
                </div>
            </div>
            <br>
        @endforeach
    @else
        <div class="alert alert-warning">No comments added</div>
    @endif
</div>