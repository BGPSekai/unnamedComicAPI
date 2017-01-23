@extends('layouts.app')

@section('head')
    <!-- iCheck -->
    <link href="gentelella/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
@endsection

@section('content')
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>會員列表</h3>
      </div>

      {{-- <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for...">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">Go!</button>
            </span>
          </div>
        </div>
      </div> --}}
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          {{-- <div class="x_title">
            <h2>Hover rows <small>Try hovering over the rows</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Settings 1</a>
                  </li>
                  <li><a href="#">Settings 2</a>
                  </li>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div> --}}
          <div class="x_content">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Avatar Type</th>
                  <th>Avatar Image</th>
                  <th>From</th>
                  <th>created_at</th>
                  <th>updated_at</th>
                </tr>
              </thead>
              <tbody>
              	@foreach($users as $user)
                <tr>
                  <th scope="row">{{ $user->id }}</th>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->avatar }}</td>
                  <td class="user-profile">
                    <a href="#" data-toggle="modal" data-target="#imageModal{{ $user->id }}">
                      <img src="{{ $user->avatar ? 'users/'.$user->id.'.'.$user->avatar : 'https://scontent-tpe1-1.xx.fbcdn.net/v/t1.0-1/11539567_843698205722529_6604788997712868611_n.jpg?oh=83c0380d52d9f8dc9f82a1a4f288fc15&oe=58B0F1EC' }}" alt="...">
                    </a>
                  </td>
                  <td>{{ $user->from }}</td>
                  <td>{{ $user->created_at }}</td>
                  <td>{{ $user->updated_at }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="text-center">
              {{ $users->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
@foreach ($users as $user)
<div class="modal fade" id="imageModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{ $user->avatar ? 'users/'.$user->id.'.'.$user->avatar : 'https://scontent-tpe1-1.xx.fbcdn.net/v/t1.0-1/11539567_843698205722529_6604788997712868611_n.jpg?oh=83c0380d52d9f8dc9f82a1a4f288fc15&oe=58B0F1EC' }}" alt="...">
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection

@section('javascript')
    <!-- iCheck -->
    <script src="gentelella/vendors/iCheck/icheck.min.js"></script>
@endsection
