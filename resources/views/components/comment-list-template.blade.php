<div class="card-header">
    <b>Comments</b>
    <br>
    <br>

    @foreach ($comments as $comment)
        <div class="card bg-white">
            <div class="card-header">
                <b>{{ $comment->user->name }}</b>
            </div>
            <div class="card-body">
                {!! $comment->content !!}
            </div>
            <div class="card-footer text-muted">
                {{ $comment->created_at }}
            </div>
        </div>
        <br>
    @endforeach
</div>