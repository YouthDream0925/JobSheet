@extends('layouts.index')

@section('breadcrumb')
<div class="page-title mb-lg-4 pb-0">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item active font-size-28">
                {{ __('global.users') }}
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="col-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title fw-normal mb-0">{{ __('global.userManage') }}</h5>
      <div>
        <a class="btn btn-default" href="{{ route('users.excel') }}">
          <i class="me-1 fa fa-database"></i>
          <span class="d-lg-inline-flex d-none">{{ __('global.excel') }}</span>
        </a>
        <a class="btn btn-success" href="{{ route('users.create') }}">
          <i class="me-1 fa fa-plus"></i> 
          <span class="d-lg-inline-flex d-none">{{ __('global.user') }}</span>
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-custom mb-0">
          <thead>
            <tr>
              <th>{{ __('global.no') }}</th>
              <th>{{ __('global.fullName') }}</th>
              <th>{{ __('global.email') }}</th>
              <th>{{ __('global.roles') }}</th>
              <th class="txt-right">{{ __('global.action') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $key => $user)
              <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $v)
                      <label>{{ $v }}</label>
                    @endforeach
                  @endif
                </td>
                <td>
                    <a href="#" class="dropdown-toggle after-none text-primary f-right" data-bs-toggle="dropdown" aria-expanded="false" title="More Action">
                      <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-opacity="0.4" d="M2 10H5C5.26522 10 5.51957 10.1054 5.70711 10.2929C5.89464 10.4804 6 10.7348 6 11V14C6 14.2652 5.89464 14.5196 5.70711 14.7071C5.51957 14.8946 5.26522 15 5 15H2C1.73478 15 1.48043 14.8946 1.29289 14.7071C1.10536 14.5196 1 14.2652 1 14V11C1 10.7348 1.10536 10.4804 1.29289 10.2929C1.48043 10.1054 1.73478 10 2 10ZM11 1H14C14.2652 1 14.5196 1.10536 14.7071 1.29289C14.8946 1.48043 15 1.73478 15 2V5C15 5.26522 14.8946 5.51957 14.7071 5.70711C14.5196 5.89464 14.2652 6 14 6H11C10.7348 6 10.4804 5.89464 10.2929 5.70711C10.1054 5.51957 10 5.26522 10 5V2C10 1.73478 10.1054 1.48043 10.2929 1.29289C10.4804 1.10536 10.7348 1 11 1ZM11 10C10.7348 10 10.4804 10.1054 10.2929 10.2929C10.1054 10.4804 10 10.7348 10 11V14C10 14.2652 10.1054 14.5196 10.2929 14.7071C10.4804 14.8946 10.7348 15 11 15H14C14.2652 15 14.5196 14.8946 14.7071 14.7071C14.8946 14.5196 15 14.2652 15 14V11C15 10.7348 14.8946 10.4804 14.7071 10.2929C14.5196 10.1054 14.2652 10 14 10H11ZM11 0C10.4696 0 9.96086 0.210714 9.58579 0.585786C9.21071 0.960859 9 1.46957 9 2V5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H14C14.5304 7 15.0391 6.78929 15.4142 6.41421C15.7893 6.03914 16 5.53043 16 5V2C16 1.46957 15.7893 0.960859 15.4142 0.585786C15.0391 0.210714 14.5304 0 14 0L11 0ZM2 9C1.46957 9 0.960859 9.21071 0.585786 9.58579C0.210714 9.96086 0 10.4696 0 11L0 14C0 14.5304 0.210714 15.0391 0.585786 15.4142C0.960859 15.7893 1.46957 16 2 16H5C5.53043 16 6.03914 15.7893 6.41421 15.4142C6.78929 15.0391 7 14.5304 7 14V11C7 10.4696 6.78929 9.96086 6.41421 9.58579C6.03914 9.21071 5.53043 9 5 9H2ZM9 11C9 10.4696 9.21071 9.96086 9.58579 9.58579C9.96086 9.21071 10.4696 9 11 9H14C14.5304 9 15.0391 9.21071 15.4142 9.58579C15.7893 9.96086 16 10.4696 16 11V14C16 14.5304 15.7893 15.0391 15.4142 15.4142C15.0391 15.7893 14.5304 16 14 16H11C10.4696 16 9.96086 15.7893 9.58579 15.4142C9.21071 15.0391 9 14.5304 9 14V11Z"></path>
                        <path fill-opacity="0.9" d="M0.585786 0.585786C0.210714 0.960859 0 1.46957 0 2V5C0 5.53043 0.210714 6.03914 0.585786 6.41421C0.960859 6.78929 1.46957 7 2 7H5C5.53043 7 6.03914 6.78929 6.41421 6.41421C6.78929 6.03914 7 5.53043 7 5V2C7 1.46957 6.78929 0.960859 6.41421 0.585786C6.03914 0.210714 5.53043 0 5 0H2C1.46957 0 0.960859 0.210714 0.585786 0.585786Z"></path>
                      </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">
                      <a href="{{ route('users.show',$user->id) }}" class="dropdown-item"><i class="me-3 fa fa-eye"></i>{{ __('global.show') }}</a>
                      <a href="{{ route('users.edit',$user->id) }}" class="dropdown-item"><i class="me-3 fa fa-pencil"></i>{{ __('global.edit') }}</a>
                      <div class="dropdown-divider"></div>
                      {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                        {!! Form::button('<i class="me-3 fa fa-trash"></i>'.__('global.delete'), ['type' =>'submit', 'class' => 'dropdown-item']) !!}
                      {!! Form::close() !!}
                    </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        @include('layouts.pagination.index', ['paginator' => $data])
      </div>
    </div>
  </div>
</div>
@endsection