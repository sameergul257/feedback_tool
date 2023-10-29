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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Category</th>
                                <th scope="col">Vote Count</th>
                                <th scope="col">Created by</th>
                                <th scope="col">Created at</th>
                                @if ($userRole == 'admin')
                                    <th scope="col">Commenting Enabled</th>
                                @endif
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($feedbacks as $index => $feedback)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $feedback->title }}</td>
                                        <td>{{ $feedback->category->name }}</td>
                                        <td>{{ $feedback->voters->count() }}</td>
                                        <td>{{ $feedback->user->name }}</td>
                                        <td>{{ $feedback->created_at }}</td>
                                        @if ($userRole == 'admin')
                                            <td class="text-center">
                                                <input type="checkbox" name="is_comment_enabled" data-id="{{ $feedback->id }}" {{ ($feedback->is_comment_enabled ? 'checked' : '') }}>
                                            </td>
                                        @endif
                                        <td>
                                            <a href="{{ route('feedback.view', ['id' => $feedback->id]) }}" class="btn btn-sm btn-success">View</a>
                                            @if ($feedback->user->id == auth()->user()->id)
                                                <a href="{{ route('feedback.create', ['id' => $feedback->id]) }}" class="btn btn-sm btn-info">Edit</a>
                                            @endif
                                            @if ($userRole == 'admin')
                                                <form method="POST" action="{{ route('feedback.destroy', ['id' => $feedback->id]) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this feedback? Deleting this feedback will also remove any associated comments.')">Delete</button>
                                                </form>
                                            @endif
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
</div>

<script>
    $(document).ready(function(){
        $(document).on('change', '[name="is_comment_enabled"]', function(){
            let id = $(this).data('id');
            let is_comment_enabled = '';
            if($(this).is(':checked')){
                is_comment_enabled = '1';
            }
            else{
                is_comment_enabled = '0';
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('feedback.changecommentingstatus') }}",
                data: {
                    _token: '{{ @csrf_token() }}',
                    id: id,
                    is_comment_enabled: is_comment_enabled,
                },
                success: function (response, textStatus, xhr) {
                    alert(response.message);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert("Error changing commenting status");
                }
            }); 
        });
    });
</script>
@endsection
