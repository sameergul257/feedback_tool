@extends('layouts.app')


@section('content')

<x-tinymce.config/>

<div class="container">
    <div class="row justify-content-center">
        @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Create Feedback</h3>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card bg-white">
                <div class="card-body">
                    <form id="feedback_form" action="{{ route('feedback.store') }}" method="post">
                        @csrf
                        @if ($feedback)
                            <input type="hidden" name="id" value="{{ $feedback->id }}">
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $feedback->title ?? '') }}" required>
                                @error('title')
                                    <div class="font-italic" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="feedback_category_id">Category</label>
                                <select class="form-select" name="feedback_category_id" id="feedback_category_id" required>
                                    <option value="">Select</option>
                                    @foreach ($feedback_categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('feedback_category_id', $feedback->feedback_category_id ?? '') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('feedback_category_id')
                                    <div class="font-italic" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="myeditorinstance" rows="5" placeholder="Write description here ...">{!! $feedback->description ?? '' !!}</textarea>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('submit', '#feedback_form', function(e){
            // Create a hidden input field
            var hiddenInput = document.createElement("textarea");
            hiddenInput.type = "hidden";
            hiddenInput.name = "description"; // Set the desired name for the input field
            hiddenInput.value = tinyMCE.get('myeditorinstance').getContent();
            hiddenInput.style.display = 'none';

            // Append the hidden input to the form
            $('#feedback_form').append(hiddenInput);
        });
    });
</script>
@endsection
