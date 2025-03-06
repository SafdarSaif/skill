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

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2">
      <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1 mt-3">{{ $user->name }}</h4>
        <p class="text-muted">{{ $user->email }} | {{ $vertical->fullName }}</p>
      </div>
      <div class="d-flex align-content-center flex-wrap gap-3">
        <div class="d-flex gap-3">
          <a href="/users/assign-verticals/{{ $user->id }}/sessions/{{ $vertical->id }}/create" class="btn btn-primary">Assign Session</a>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="session-table" class="table table-striped">
              <thead>
                <tr>
                  <th>Session</th>
                  <th>Start Date</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                @if ($userSharings->count() > 0)
                  @foreach ($userSharings as $userSharing)
                    <tr>
                      <td>{{ $userSharing->admissionSession->name }}</td>
                      <td>{{ Carbon\Carbon::createFromFormat('Y-m-d', $userSharing->start_date)->format('d-m-Y') }}</td>
                      </td>
                      <td class="text-end"><i class="ti ti-edit"></i></td>
                    </tr>
                  @endforeach
                @else
                  <tr class="text-center">
                    <td colspan="3">No sessions assigned yet.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
