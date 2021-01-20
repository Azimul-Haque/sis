@extends('layouts.app')
@section('title') ড্যাশবোর্ড | একক | {{ $site->name }} @endsection

@section('third_party_stylesheets')
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
@endsection

@section('content')
	@section('page-header') {{ $site->name }} @endsection
    <div class="container-fluid">
    	<div class="info-box mb-3">
	      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hand-holding-usd"></i></span>

	      <div class="info-box-content">
	        <span class="info-box-text">{{ bangla(date('F Y')) }}</span>
	        <span class="info-box-number">৳ {{ $monthlyexpensetotalcurrent ? $monthlyexpensetotalcurrent->totalamount : 0 }}</span>
	      </div>
	      <!-- /.info-box-content -->
	    </div>
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">ব্যয়ের তালিকা</h3>

            <div class="card-tools">
            	<button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addExpenseModal">
            		<i class="fas fa-hand-holding-usd"></i> নতুন ব্যয়
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
                                <span class="text-black-50">যোগ করেছেনঃ</span> {{ $expense->user->name }} <span class="text-black-50"><br/>
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
                                আপনি কি নিশ্চিতভাবে এই ব্যয়টি ডিলেট করতে চান?<br/>
                                <center><big><b>$ {{ $expense->amount }}</b></big><br/>
                                    <small><i class="fas fa-user"></i> {{ $expense->user->name }}, <i class="fas fa-calendar-alt"></i> {{ date('F d, Y', strtotime($expense->created_at)) }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.expense.delete', $expense->id) }}" class="btn btn-danger">ডিলেট করুন</a>
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

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">ব্যয়ের তালিকা</h3>

            <div class="card-tools">
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table" id="example1_wrapper">
              <thead>
                <tr>
                  <th>মাস</th>
                  <th>মোট ব্যয়</th>
                </tr>
              </thead>
              <tbody>
                @foreach($monthlyexpenses as $monthlyexpense)
                  <tr>
                    <td>{{ date('F Y', strtotime($monthlyexpense->created_at)) }}</td>
                    <td>৳ {{ $monthlyexpense->totalamount }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>

    </div>

    {{-- Add Expense Modal Code --}}
    {{-- Add Expense Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addExpenseModalLabel">ব্যয় যোগ করুন</h5>
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
	                		<option selected="" disabled="" value="">ক্যাটাগরি নির্ধারণ করুন</option>
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
	            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
	            <button type="submit" class="btn btn-success">দাখিল করুন</button>
	          </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Add Expense Modal Code --}}
    {{-- Add Expense Modal Code --}}
@endsection

@section('third_party_scripts')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript" src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" >
    $.noConflict();
    jQuery( document ).ready(function( $ ) {
        $('#example1_wrapper').DataTable({
          "paging": true,
          "pageLength": 5,
          "lengthChange": false,
          "ordering": true,
          "info": true,
          "autoWidth": true,
        });
    });
    
  </script>
@endsection