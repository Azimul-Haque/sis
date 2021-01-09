@extends('layouts.app')
@section('title') Dashboard | Balance @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') Total Balance: ৳ {{ $totalbalance }} @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">Balance</h3>

            <div class="card-tools">
            	<button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addBalanceModal">
            		<i class="fas fa-coins"></i> Add
            	</button>
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
                @foreach($balances as $balance)
                	<tr>
                        <td style="line-height: 1;">
                            <span class="badge bg-success"><big>৳ {{ $balance->amount }}</big></span><br/>
                            <small>
                                <span class="text-black-50">Added by</span> {{ $balance->user->name }} <span class="text-black-50"><br/>
                                </span> {{ date('F d, Y h:i A', strtotime($balance->created_at)) }}
                            </small> 
                        </td>
                		<td align="right" width="40%">
                			@if(Auth::user()->role == 'admin')
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteBalanceModal{{ $balance->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif
                		</td>
                        {{-- Delete Balance Modal Code --}}
                        {{-- Delete Balance Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteBalanceModal{{ $balance->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteBalanceModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteBalanceModalLabel">Delete Amount</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                Are you sure to delete this balance?<br/>
                                <center><big><b>$ {{ $balance->amount }}</b></big><br/>
                                    <small><i class="fas fa-user"></i> {{ $balance->user->name }}, <i class="fas fa-calendar-alt"></i> {{ date('F d, Y', strtotime($balance->created_at)) }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <a href="{{ route('dashboard.balance.delete', $balance->id) }}" class="btn btn-danger">Delete</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- Delete Balance Modal Code --}}
                        {{-- Delete Balance Modal Code --}}
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $balances->links() }}
    </div>

    {{-- Add Balance Modal Code --}}
    {{-- Add Balance Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addBalanceModal" tabindex="-1" role="dialog" aria-labelledby="addBalanceModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addBalanceModalLabel">Add Balance</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.balance.store') }}">
	          <div class="modal-body">
	                @csrf

	                <div class="input-group mb-3">
	                    <input type="number"
	                           name="amount"
	                           class="form-control"
	                           value="{{ old('amount') }}"
	                           placeholder="Add Balance" required>
	                    <div class="input-group-append">
	                        <div class="input-group-text"><span class="fas fa-money-check-alt"></span></div>
	                    </div>
	                </div>
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	            <button type="submit" class="btn btn-success">Save</button>
	          </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Add Balance Modal Code --}}
    {{-- Add Balance Modal Code --}}
@endsection

@section('third_party_scripts')

@endsection