@extends('layouts.app')
@section('title') Dashboard | Single | {{ $site->name }} @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') {{ $site->name }} @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">Expenses</h3>

            <div class="card-tools">
            	<button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addExpenseModal">
            		<i class="fas fa-hand-holding-usd"></i> Add
            	</button>
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
                @foreach($expenses as $expense)
                	<tr>
                        <td style="line-height: 1;">
                            <span class="badge bg-info"><big>৳ {{ $expense->amount }}</big></span><br/>
                            <small>
                                <span class="text-black-50">Added by</span> {{ $expense->user->name }} <span class="text-black-50"><br/>
                                </span> {{ date('F d, Y h:i A', strtotime($expense->created_at)) }}
                            </small> 
                        </td>
                		<td align="right" width="40%">
                			@if(Auth::user()->role == 'admin')
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteExpenseModal{{ $expense->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif
                		</td>
                        {{-- Delete Expense Modal Code --}}
                        {{-- Delete Expense Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteExpenseModal{{ $expense->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteExpenseModalLabel">Delete Expense</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                Are you sure to delete this expense?<br/>
                                <center><big><b>$ {{ $expense->amount }}</b></big><br/>
                                    <small><i class="fas fa-user"></i> {{ $expense->user->name }}, <i class="fas fa-calendar-alt"></i> {{ date('F d, Y', strtotime($expense->created_at)) }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <a href="{{ route('dashboard.expense.delete', $expense->id) }}" class="btn btn-danger">Delete</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- Delete Expense Modal Code --}}
                        {{-- Delete Expense Modal Code --}}
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $expenses->links() }}
    </div>

    {{-- Add Expense Modal Code --}}
    {{-- Add Expense Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.expense.store') }}">
	          <div class="modal-body">
	                @csrf

	                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required>
	                <input type="hidden" name="site_id" value="{{ $site->id }}" required>

	                <div class="input-group mb-3">
	                	<select name="category_id" class="form-control" required>
	                		<option selected="" disabled="" value="">Select Category</option>
	                		@foreach($categories as $category)
	                			<option value="{{ $category->id }}">{{ $category->name }}</option>
	                		@endforeach
	                	</select>
	                    <div class="input-group-append">
	                        <div class="input-group-text"><span class="fas fa-tags"></span></div>
	                    </div>
	                </div>

	                <div class="input-group mb-3">
	                    <input type="number"
	                           name="amount"
	                           class="form-control"
	                           value="{{ old('amount') }}"
	                           placeholder="Write Amount" required>
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
    {{-- Add Expense Modal Code --}}
    {{-- Add Expense Modal Code --}}
@endsection

@section('third_party_scripts')

@endsection