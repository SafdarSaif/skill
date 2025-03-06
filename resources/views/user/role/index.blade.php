@extends('layouts.main')
@section('content')

  <h4 class="mb-4">Roles List</h4>

  <p class="mb-4">A role provided access to predefined menus and features so that depending on <br> assigned role an
    administrator can have access to what user needs.</p>
  <!-- Role cards -->
  <div class="row g-4">
    @foreach ($roles as $role)
      <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h6 class="fw-normal mb-2">Total 4 users</h6>
              <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy"
                  class="avatar avatar-sm pull-up">
                  <img class="rounded-circle" src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar">
                </li>
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Allen Rieske"
                  class="avatar avatar-sm pull-up">
                  <img class="rounded-circle" src="{{ asset('assets/img/avatars/12.png') }}" alt="Avatar">
                </li>
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Julee Rossignol"
                  class="avatar avatar-sm pull-up">
                  <img class="rounded-circle" src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar">
                </li>
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Kaith D'souza"
                  class="avatar avatar-sm pull-up">
                  <img class="rounded-circle" src="{{ asset('assets/img/avatars/3.png') }}" alt="Avatar">
                </li>
                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="John Doe"
                  class="avatar avatar-sm pull-up">
                  <img class="rounded-circle" src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar">
                </li>
              </ul>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-1">
              <div class="role-heading">
                <h4 class="mb-1">{{ $role->name }}</h4>
                </h4>
                @can('edit roles')
                  <a href="javascript:;" onclick="edit('{{ route('users.roles.edit', ['id' => $role->id]) }}', 'modal-xl')"
                    class="role-edit-modal"><span>Edit Role</span></a>
                @endcan
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card h-100">
        <div class="row h-100">
          <div class="col-sm-5">
            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
              <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid mt-sm-4 mt-md-0"
                alt="add-new-roles" width="83">
            </div>
          </div>
          <div class="col-sm-7">
            <div class="card-body text-sm-end text-center ps-sm-0">
              <button onclick="add('{{ route('users.roles.create') }}', 'modal-xl')"
                class="btn btn-primary mb-2 text-nowrap add-new-role">Add New Role</button>
              <p class="mb-0 mt-1">Add role, if it does not exist</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Role cards -->

@endsection
