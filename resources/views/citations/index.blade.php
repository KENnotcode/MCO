@extends('adminLayout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users for Citation</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="usersTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Extension Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->middle_name }}</td>
                                <td>{{ $user->extension_name }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#citationModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                        Modify
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>

<!-- Citation Modal -->
<div class="modal fade" id="citationModal" tabindex="-1" role="dialog" aria-labelledby="citationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="citationModalLabel">Add Citation for <span id="userName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <form id="citationForm">
                            @csrf
                            <input type="hidden" id="userId" name="user_id">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" name="category_id" required>
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="violation">Violation</label>
                                <select class="form-control" id="violation" name="violation_id" required>
                                    <option value="">Select a category first</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="offense">Offense</label>
                                <select class="form-control" id="offense" name="offense" required>
                                    <option value="">Select a violation first</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h5>Current Citations</h5>
                        <ul id="userCitationsList" class="list-group">
                            <!-- User citations will be loaded here via AJAX -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="addMore">Add More</button>
                <button type="button" class="btn btn-primary" id="saveCitations">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let citationsToAdd = [];

        function loadUserCitations(userId) {
            $.get(`/users/${userId}/citations`, function(data) {
                let userCitationsList = $('#userCitationsList');
                userCitationsList.empty();
                data.forEach(function(citation) {
                    let offenseText = citation.offense.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    let listItem = `
                        <li class="list-group-item d-flex justify-content-between align-items-center" id="citation-${citation.id}">
                            ${citation.violation.name} - ${offenseText}
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteCitation(${citation.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        </li>`;
                    userCitationsList.append(listItem);
                });
            });
        }

        $('#citationModal').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let userId = button.data('user-id');
            let userName = button.data('user-name');

            let modal = $(this);
            modal.find('#userId').val(userId);
            modal.find('#userName').text(userName);

            // Clear previous data
            citationsToAdd = [];
            updateCitationList();
            $('#citationForm')[0].reset();
            $('#violation').html('<option value="">Select a category first</option>');
            $('#offense').html('<option value="">Select a violation first</option>');


            // Fetch and display user's existing citations
            loadUserCitations(userId);
        });

        $('#category').on('change', function() {
            var categoryId = $(this).val();
            var violationSelect = $('#violation');
            violationSelect.html('<option value="">Loading...</option>');
            $('#offense').html('<option value="">Select a violation first</option>');

            if (categoryId) {
                $.get('{{ route('api.violations.by.category') }}', { category_id: categoryId }, function(data) {
                    violationSelect.html('<option value="">Select a violation</option>');
                    $.each(data, function(key, violation) {
                        violationSelect.append('<option value="' + violation.id + '">' + violation.name + '</option>');
                    });
                });
            } else {
                violationSelect.html('<option value="">Select a category first</option>');
            }
        });

        $('#violation').on('change', function() {
            var violationId = $(this).val();
            var offenseSelect = $('#offense');
            offenseSelect.html('<option value="">Loading...</option>');

            if (violationId) {
                $.get('{{ route('api.offenses.for.violation') }}', { violation_id: violationId }, function(data) {
                    offenseSelect.html('<option value="">Select an offense</option>');
                    $.each(data, function(key, offense) {
                        offenseSelect.append('<option value="' + offense.key + '">' + offense.value + '</option>');
                    });
                });
            } else {
                offenseSelect.html('<option value="">Select a violation first</option>');
            }
        });

        $('#addMore').on('click', function() {
            let violationId = $('#violation').val();
            let violationName = $('#violation option:selected').text();
            let offense = $('#offense').val();
            let offenseName = $('#offense option:selected').text();
            let userId = $('#userId').val();

            if (violationId && offense) {
                citationsToAdd.push({
                    user_id: userId,
                    violation_id: violationId,
                    violation_name: violationName,
                    offense: offense,
                    offense_name: offenseName
                });
                updateCitationList();
                $('#citationForm')[0].reset();
            } else {
                alert('Please select a violation and an offense.');
            }
        });

        function updateCitationList() {
            let list = $('#userCitationsList');
            // We only want to update the part of the list with new citations
            list.find('.new-citation').remove(); 
            citationsToAdd.forEach(function(citation, index) {
                let listItem = `
                    <li class="list-group-item d-flex justify-content-between align-items-center new-citation">
                        ${citation.violation_name} - ${citation.offense_name}
                        <button type="button" class="btn btn-danger btn-sm" data-index="${index}" onclick="removeCitation(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </li>`;
                list.append(listItem);
            });
        }

        window.removeCitation = function(button) {
            let index = $(button).data('index');
            citationsToAdd.splice(index, 1);
            updateCitationList();
        }

        window.deleteCitation = function(citationId) {
            if (confirm('Are you sure you want to delete this citation?')) {
                $.ajax({
                    url: `/citations/${citationId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $(`#citation-${citationId}`).remove();
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting the citation.');
                    }
                });
            }
        }

        $('#saveCitations').on('click', function() {
            if (citationsToAdd.length > 0) {
                $.ajax({
                    url: '{{ route('citations.store') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        citations: citationsToAdd
                    },
                    success: function(response) {
                        $('#citationModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred while saving citations.');
                    }
                });
            } else {
                // If nothing to add, just close the modal
                $('#citationModal').modal('hide');
            }
        });
    });
</script>
@endsection
