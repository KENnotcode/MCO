@extends('adminLayout')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('violations.index') }}" method="GET">
                <div class="input-group">
                    <select class="form-control" name="category_id" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addCategoryModal">
                Add Category
            </button>
            <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#addViolationModal">
                Add Violation
            </button>
            <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#manageCategoriesModal">
                Manage Categories
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Violations</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <table id="violationsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>1st Offense</th>
                                <th>2nd Offense</th>
                                <th>3rd Offense</th>
                                <th>4th Offense</th>
                                <th>Penalty</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($violations as $violation)
                            <tr>
                                <td>{{ $violation->id }}</td>
                                <td>{{ $violation->name }}</td>
                                <td>{{ $violation->first_offense }}</td>
                                <td>{{ $violation->second_offense }}</td>
                                <td>{{ $violation->third_offense }}</td>
                                <td>{{ $violation->fourth_offense }}</td>
                                <td>{{ $violation->penalty }}</td>
                                <td>
                                    <a href="{{ route('violations.edit', $violation->id) }}" class="btn btn-info btn-sm">Edit</a>
                                    <form action="{{ route('violations.destroy', $violation->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('violations.storeCategory') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="manageCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="manageCategoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageCategoriesModalLabel">Manage Categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach($categories as $category)
                        <li class="list-group-item">
                            <div class="category-view">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="category-name">{{ $category->name }}</span>
                                    <div>
                                        <button class="btn btn-warning btn-sm edit-category-btn">Edit</button>
                                        <form action="{{ route('violations.destroyCategory', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category? This will also delete ALL violations under this category. This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="edit-category-form" style="display: none; margin-top: 10px;">
                                <form action="{{ route('violations.updateCategory', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success">Save</button>
                                            <button type="button" class="btn btn-secondary cancel-edit-btn">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addViolationModal" tabindex="-1" role="dialog" aria-labelledby="addViolationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addViolationModalLabel">Add New Violation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('violations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="violation_category_id">Category</label>
                        <select class="form-control" name="violation_category_id" required>
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Violation Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="first_offense">1st Offense</label>
                        <input type="text" class="form-control" id="first_offense" name="first_offense">
                    </div>
                    <div class="form-group">
                        <label for="second_offense">2nd Offense</label>
                        <input type="text" class="form-control" id="second_offense" name="second_offense">
                    </div>
                    <div class="form-group">
                        <label for="third_offense">3rd Offense</label>
                        <input type="text" class="form-control" id="third_offense" name="third_offense">
                    </div>
                    <div class="form-group">
                        <label for="fourth_offense">4th Offense</label>
                        <input type="text" class="form-control" id="fourth_offense" name="fourth_offense">
                    </div>
                    <div class="form-group">
                        <label for="penalty">Penalty</label>
                        <input type="text" class="form-control" id="penalty" name="penalty">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Violation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // DataTable initialization
    if (!$.fn.DataTable.isDataTable('#violationsTable')) {
        $("#violationsTable").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#violationsTable_wrapper .col-md-6:eq(0)');
    }

    // Manage Categories Modal Logic - using event delegation
    $('#manageCategoriesModal').on('click', '.edit-category-btn', function() {
        var listItem = $(this).closest('.list-group-item');
        listItem.find('.category-view').hide();
        listItem.find('.edit-category-form').show();
    });

    $('#manageCategoriesModal').on('click', '.cancel-edit-btn', function() {
        var listItem = $(this).closest('.list-group-item');
        listItem.find('.edit-category-form').hide();
        listItem.find('.category-view').show();
    });
});
</script>
@endsection
