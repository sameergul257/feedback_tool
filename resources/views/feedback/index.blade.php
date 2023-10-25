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
                <h3>Feedback List</h3>
                <a href="{{ route('feedback.create') }}" class="btn btn-primary">Add Feedback</a>
            </div>
        </div>

        {{-- Table --}}
        <div class="col-md-12 mt-4">
            <div class="card bg-white">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Vote Count</th>
                            <th scope="col">Created by</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($feedbacks as $index => $feedback)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $feedback->title }}</td>
                                    <td>{{ $feedback->category->name }}</td>
                                    <td>Vote Count HERE</td>
                                    <td>{{ $feedback->user->name }}</td>
                                    <td>{{ $feedback->created_at }}</td>
                                    <td>
                                        <a href="{{ route('feedback.view', ['id' => $feedback->id]) }}" class="btn btn-sm btn-success">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection