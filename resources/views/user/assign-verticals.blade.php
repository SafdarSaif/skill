@extends('layouts/layoutMaster')

@section('title', 'User | Assign Vertical')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js'])
@endsection

@section('page-script')
@endsection

@section('content')

  <div class="row g-4 mb-4">
    <div class="col-md-12">
      <h5 class="mb-0">{{ $user->name }}</h5>
      <small class="text-muted">{{ $user->email }}</small>
    </div>
    @foreach ($verticals as $vertical)
      <div class="col-md-3 col-lg-2">
        <a href="/users/assign-verticals/{{ $user->id }}/sessions/{{ $vertical->id }}">
          <div class="card">
            <div class="card-body text-center">
              <img src="{{ asset($vertical->logo) }}" width="100%" height="55px">
              <p class="fs-12 mt-3 mb-0">{{ $vertical->short_name . ' (' . $vertical->vertical_name . ')' }}</p>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

@endsection
