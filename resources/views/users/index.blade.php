@extends('layouts.app')
@section('title') Dashboard | Users @endsection

@section('content')
	@section('page-header') Users @endsection
    <div class="container-fluid">
        <div class="row">
        	<div class="col-md-6">
        		<div class="card">
	              <div class="card-header">
	                <h3 class="card-title">Users</h3>

	                <div class="card-tools">
	                  {{-- <ul class="pagination pagination-sm float-right">
	                    <li class="page-item"><a class="page-link" href="#">«</a></li>
	                    <li class="page-item"><a class="page-link" href="#">1</a></li>
	                    <li class="page-item"><a class="page-link" href="#">2</a></li>
	                    <li class="page-item"><a class="page-link" href="#">3</a></li>
	                    <li class="page-item"><a class="page-link" href="#">»</a></li>
	                  </ul> --}}
	                </div>
	              </div>
	              <!-- /.card-header -->
	              <div class="card-body p-0">
	                <table class="table">
	                  <thead>
	                    <tr>
	                      <th style="width: 10px">#</th>
	                      <th>Name</th>
	                      <th>Role</th>
	                      <th style="width: 40px">Action</th>
	                    </tr>
	                  </thead>
	                  <tbody>
	                    {{-- <tr>
	                      <td>1.</td>
	                      <td>Update software</td>
	                      <td>
	                        <div class="progress progress-xs">
	                          <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
	                        </div>
	                      </td>
	                      <td><span class="badge bg-danger">55%</span></td>
	                    </tr> --}}
	                    @php
	                    	$i = 1;
	                    @endphp
	                    @foreach($users as $user)
	                    	<tr>
	                    		<td>{{ $i }}</td>
	                    		<td>{{ $user->name }}<br/><small class="text-black-50">{{ $user->mobile }}</small></td>
	                    		<td><span class="badge @if($user->role == 'admin') bg-success @else bg-info @endif">{{ ucfirst($user->role) }}</span></td>
	                    		<td>
	                    			<button type="button" class="btn btn-primary btn-sm">
	                    				<i class=""></i>
	                    			</button>
	                    			<button type="button" class="btn btn-danger btn-sm">
	                    				<i class=""></i>
	                    			</button>
	                    		</td>
	                    	</tr>
	                    	@php
	                    		$i++;
	                    	@endphp
	                    @endforeach
	                  </tbody>
	                </table>
	              </div>
	              <!-- /.card-body -->
	            </div>
        	</div>
        	<div class="col-md-6">s</div>
        </div>
    </div>
@endsection