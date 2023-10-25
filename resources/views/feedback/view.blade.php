@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>View Feedback</h3>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for=""><b>Title</b></label>
                            <div>{{ $feedback->title }}</div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Category</b></label>
                            <div>{{ $feedback->category->name }}</div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Submitted by</b></label>
                            <div>{{ $feedback->user->name }}</div>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('feedback.vote') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $feedback->id }}">
                                @if (!$voted)
                                    <button class="btn btn-primary">Vote</button>
                                @else
                                    <button class="btn btn-danger">Remove Vote</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card bg-white">
                <div class="card-header">
                    <b>Description</b>
                </div>
                <div class="card-body">
                    {!! $feedback->description !!}
                </div>
            </div>
            <br>
            <div class="card bg-white">
                <div class="card-header">
                    <b>Leave a Comment</b>
                </div>
                <div class="card-body">
                    <form action="{{ route('feedback.add_comment') }}" method="post">
                        @csrf
                        <input type="hidden" name="feedback_id" value="{{ $feedback->id }}">
                        <textarea class="form-control" name="content" id="content" rows="5" placeholder="Write your comment here ..." required></textarea>
                        @error('content')
                            <div class="font-italic" style="color: red">{{ $message }}</div>
                        @enderror
                        <br>
                        <button class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
            <br>
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
        </div>

    </div>
</div>
@endsection
