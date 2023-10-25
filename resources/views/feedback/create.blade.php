@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h3>Create Feedback</h3>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card bg-white">
                <div class="card-body">
                    <form action="{{ route('feedback.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" id="title" required>
                                @error('title')
                                    <div class="font-italic" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="feedback_category_id">Category</label>
                                <select class="form-select" name="feedback_category_id" id="feedback_category_id" required>
                                    <option value="">Select</option>
                                    @foreach ($feedback_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('feedback_category_id')
                                    <div class="font-italic" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="10" required></textarea>
                                @error('description')
                                    <div class="font-italic" style="color: red">{{ $message }}</div>
                                @enderror
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
@endsection
