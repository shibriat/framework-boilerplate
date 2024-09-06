@extends('main')

@section('page-title', 'Users List')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User List</h3>
                <div class="card-tools">
                    <a href="{{ route('register') }}" class="btn btn-primary">New User</a>
                </div>
            </div>

            <div class="card-body">
                <table id="users-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        {{ $role->name }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($user->permissions as $permission)
                                        {{ $permission->name }}
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-primary edit-btn" data-toggle="modal" data-target="#editUserModal"
                                        data-id="{{ $user->id }}"
                                        data-roles="{{ implode(',', $user->roles->pluck('name')->toArray()) }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="user-id" name="user_id">
                        <div class="form-group">
                            <label for="user-roles">Roles</label>
                            <select class="form-control" id="user-roles" name="roles[]" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#users-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });

            $('.edit-btn').on('click', function() {
                const userId = $(this).data('id');
                const userRoles = $(this).data('roles').split(',');

                $('#user-id').val(userId);

                $('#user-roles').val(userRoles).trigger('change');

                $('#editUserModal').modal('show');
            });

            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/users/' + $('#user-id').val() + '/update-roles',
                    type: 'PUT',
                    data: {
                        roles: $('#user-roles').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('User roles updated successfully');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error updating user roles');
                        console.log(xhr.responseText);
                    }
                });
            });

        });
    </script>
@endsection
