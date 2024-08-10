<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>จัดการข้อมูลผู้ใช้งานระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>จัดการข้อมูลผู้ใช้งานระบบ</h2>

        <!-- Form for creating new user -->
        <form id="createUserForm">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อ - นามสกุล</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">ที่อยู่อีเมล</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="user_status" class="form-label">สถานะผู้ใช้งาน</label>
                <select class="form-select" id="user_status" name="user_status">
                    <option value="ผู้ใช้งานทั่วไป">ผู้ใช้งานทั่วไป</option>
                    <option value="แอดมินผู้ดูแลระบบ">แอดมินผู้ดูแลระบบ</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">สร้างบัญชีผู้ใช้งาน</button>
        </form>

        <!-- Form for editing user -->
        <form id="editUserForm" style="display: none;">
            <input type="hidden" id="edit_user_id">
            <div class="mb-3">
                <label for="edit_name" class="form-label">ชื่อ - นามสกุล</label>
                <input type="text" class="form-control" id="edit_name" name="name">
            </div>
            <div class="mb-3">
                <label for="edit_email" class="form-label">ที่อยู่อีเมล</label>
                <input type="email" class="form-control" id="edit_email" name="email">
            </div>
            <div class="mb-3">
                <label for="edit_password" class="form-label">รหัสผ่าน (เว้นว่างไว้หากไม่มีการเปลี่ยนแปลง)</label>
                <input type="password" class="form-control" id="edit_password" name="password">
            </div>
            <div class="mb-3">
                <label for="edit_user_status" class="form-label">สถานะผู้ใช้งาน</label>
                <select class="form-select" id="edit_user_status" name="user_status">
                    <option value="ผู้ใช้งานทั่วไป">ผู้ใช้งานทั่วไป</option>
                    <option value="แอดมินผู้ดูแลระบบ">แอดมินผู้ดูแลระบบ</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">อัปเดทข้อมูลผู้ใช้งาน</button>
            <button type="button" class="btn btn-secondary" id="cancelEdit">ยกเลิก</button>
        </form>

        <!-- Table of users -->
        <table class="table table-bordered mt-5">
            <thead>
                <tr>
                    <th>หมายเลข</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>ที่อยู่อีเมล</th>
                    <th>สถานะผู้ใช้งานระบบ</th>
                    <th>แสดงผล</th>
                </tr>
            </thead>
            <tbody id="usersTable">
                @foreach ($users as $user)
                    <tr data-id="{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <select class="form-select update-status">
                                <option value="ผู้ใช้งานทั่วไป"
                                    {{ $user->user_status == 'ผู้ใช้งานทั่วไป' ? 'selected' : '' }}>ผู้ใช้งานทั่วไป
                                </option>
                                <option value="แอดมินผู้ดูแลระบบ"
                                    {{ $user->user_status == 'แอดมินผู้ดูแลระบบ' ? 'selected' : '' }}>แอดมินผู้ดูแลระบบ
                                </option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-warning edit-user">แก้ไข</button>
                            <button class="btn btn-danger delete-user">ลบข้อมูล</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Create User
            $('#createUserForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/users',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // Edit User
            $('.edit-user').click(function() {
                var userId = $(this).closest('tr').data('id');

                $.ajax({
                    url: '/users/' + userId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#edit_user_id').val(response.id);
                        $('#edit_name').val(response.name);
                        $('#edit_email').val(response.email);
                        $('#edit_user_status').val(response.user_status);
                        $('#createUserForm').hide();
                        $('#editUserForm').show();
                    }
                });
            });

            // Update User
            $('#editUserForm').submit(function(e) {
                e.preventDefault();
                var userId = $('#edit_user_id').val();

                $.ajax({
                    url: '/users/' + userId,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // Cancel Edit
            $('#cancelEdit').click(function() {
                $('#editUserForm').hide();
                $('#createUserForm').show();
            });

            // Delete User
            $('.delete-user').click(function() {
                if (confirm('Are you sure you want to delete this user?')) {
                    var userId = $(this).closest('tr').data('id');

                    $.ajax({
                        url: '/users/' + userId,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.status === 'success') {
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>








<style>
    *{
        font-family: "Noto Sans Thai", sans-serif;
    }

    body {
        background-color: #f8f9fa;
        /* font-family: Arial, sans-serif; */
    }

    .container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    h2 {
        margin-bottom: 20px;
        color: #343a40;
    }

    .form-control,
    .form-select {
        border-radius: 4px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
    }

    .form-label {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        border-radius: 4px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #007bff;
        color: #ffffff;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-warning,
    .btn-danger {
        border-radius: 4px;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .form-select {
        background-color: #ffffff;
        border-color: #ced4da;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
    }

    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }
</style>
