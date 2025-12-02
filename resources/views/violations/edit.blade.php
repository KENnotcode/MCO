@extends('adminLayout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Violation</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('violations.update', $violation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Violation Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $violation->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="first_offense">1st Offense</label>
                            <input type="text" class="form-control" id="first_offense" name="first_offense" value="{{ $violation->first_offense }}">
                        </div>
                        <div class="form-group">
                            <label for="second_offense">2nd Offense</label>
                            <input type="text" class="form-control" id="second_offense" name="second_offense" value="{{ $violation->second_offense }}">
                        </div>
                        <div class="form-group">
                            <label for="third_offense">3rd Offense</label>
                            <input type="text" class="form-control" id="third_offense" name="third_offense" value="{{ $violation->third_offense }}">
                        </div>
                        <div class="form-group">
                            <label for="fourth_offense">4th Offense</label>
                            <input type="text" class="form-control" id="fourth_offense" name="fourth_offense" value="{{ $violation->fourth_offense }}">
                        </div>
                        <div class="form-group">
                            <label for="penalty">Penalty</label>
                            <input type="text" class="form-control" id="penalty" name="penalty" value="{{ $violation->penalty }}">
                        </div>
                        <div class="form-group">
                            <label for="violation_category_id">Category</label>
                            <select class="form-control" id="violation_category_id" name="violation_category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $violation->violation_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Violation</button>
                        <a href="{{ route('violations.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
