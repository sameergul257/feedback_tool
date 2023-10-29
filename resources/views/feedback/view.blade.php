@extends('layouts.app')

@section('content')

<x-tinymce.config/>

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
            <div class="d-flex flex-row">
                <h3>View Feedback</h3>
                <div style="margin-left: 20px">
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

        <div class="col-md-12 mt-4">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-6">
                            <label for=""><b>Title</b></label>
                            <div>{{ $feedback->title }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for=""><b>Category</b></label>
                            <div>{{ $feedback->category->name }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for=""><b>Submitted by</b></label>
                            <div>{{ $feedback->user->name }}</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for=""><b>Last Updated</b></label>
                            <div>{{ $feedback->updated_at->format('M d Y h:i:s A') }}</div>
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
            @if ($feedback->is_comment_enabled)
                <div class="card bg-white">
                    <div class="card-header">
                        <b>Leave a Comment</b>
                    </div>
                    <div class="card-body">
                        <form id="comment_form" action="{{ route('feedback.add_comment') }}" method="post">
                            @csrf
                            <input type="hidden" name="feedback_id" value="{{ $feedback->id }}">
                            <textarea class="form-control" id="myeditorinstance" rows="5" placeholder="Write your comment here ..."></textarea>
                            @error('content')
                                <div class="font-italic" style="color: red">{{ $message }}</div>
                            @enderror
                            <br>
                            <div id="comment_action_status_div" class="alert alert-success" style="display: none"></div>
                            <br>
                            <button class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">Comments disabled for this feedback by Admin</div>
            @endif


            <br>
            <x-comment-list :feedback_id="$feedback->id"/>
            
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        $(document).on('submit', '#comment_form', function(e){
            e.preventDefault();

            var data = $(this).serializeArray();
            data.push({name: 'content', value: tinyMCE.get('myeditorinstance').getContent()});

            $.ajax({
                type: 'POST',
                url: "{{ route('feedback.add_comment') }}",
                data: data,
                success: function (response, textStatus, xhr) {
                    $("#comment_action_status_div").html(response.message);
                    if(response.status){
                        get_comments_list();
                        $("#comment_action_status_div").addClass('alert-success').removeClass('alert-danger');
                        $("#comment_action_status_div").show();
                        setTimeout(function() {
                            $("#comment_action_status_div").hide();
                        }, 4000);
                        tinyMCE.get('myeditorinstance').setContent('');
                    }
                    else{
                        $("#comment_action_status_div").removeClass('alert-success').addClass('alert-danger');
                        $("#comment_action_status_div").show();
                        setTimeout(function() {
                            $("#comment_action_status_div").hide();
                        }, 4000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#comment_action_status_div").html(response.message);
                    $("#comment_action_status_div").removeClass('alert-success').addClass('alert-danger');
                    $("#comment_action_status_div").show();
                    setTimeout(function() {
                        $("#comment_action_status_div").hide();
                    }, 4000);
                    var response = XMLHttpRequest;
                    console.error(response);
                }
            }); 
        })
    });
</script>

@endsection