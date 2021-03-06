@extends('layouts.app')
@section('title') ড্যাশবোর্ড | একক | {{ $user->name }} @endsection

@section('third_party_stylesheets')
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
  <style type="text/css">
    #img-upload{
        width: 200px;
        height: auto;
    }
  </style>
@endsection

@section('content')
	@section('page-header') {{ $user->name }} @endsection
    <div class="container-fluid">
    	<div class="info-box mb-3">
	      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hand-holding-usd"></i></span>

	      <div class="info-box-content">
	        <span class="info-box-text">{{ bangla(date('F Y')) }}</span>
          <span class="info-box-number">৳ 0</span>
	        {{-- <span class="info-box-number">৳ {{ $monthlyexpensetotalcurrent ? $monthlyexpensetotalcurrent->totalamount : 0 }}</span> --}}
	      </div>
	      <!-- /.info-box-content -->
	    </div>

    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.users.single', $user->id) }}" >প্রদানকৃত অর্থের তালিকা</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active">ব্যয়ের তালিকা</a>
          </li>
        </ul>
      </div>
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
            @foreach($user->expenses->sortByDesc('created_at') as $expense)
              <tr>
                <td style="line-height: 1;">
                    <span class="badge bg-primary">৳ {{ $expense->amount }}</span>
                    <span class="badge bg-success">{{ $expense->category->name }}</span>
                    <span class="badge bg-info">{{ $expense->qty }}</span><br/>
                    <small>
                        <span class="text-black-50">যোগ করেছেনঃ</span> {{ $expense->user->name }}<br/>
                        <small>{{ date('F d, Y h:i A', strtotime($expense->created_at)) }}</small>
                    </small> 
                </td>
                <td align="right" width="40%">
                  @if($expense->image != null)
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#receiptExpenseModal{{ $expense->id }}">
                        <i class="fas fa-image"></i>
                    </button>
                  @endif
                  @if(Auth::user()->role == 'admin')
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteExpenseModal{{ $expense->id }}">
                          <i class="fas fa-trash-alt"></i>
                      </button>
                  @endif
                </td>
                    
                    {{-- Expense Receipt Modal Code --}}
                    {{-- Expense Receipt Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="receiptExpenseModal{{ $expense->id }}" tabindex="-1" role="dialog" aria-labelledby="receiptExpenseModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="receiptExpenseModalLabel">ব্যয় রিসিট</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p style="width: 100%; overflow-y: scroll;">
                              @if($expense->image != null)
                                <img src="{{ asset('images/expenses/' . $expense->image) }}">
                              @endif
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    {{-- Expense Receipt Modal Code --}}
                    {{-- Expense Receipt Modal Code --}}

                    {{-- Delete Expense Modal Code --}}
                    {{-- Delete Expense Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="deleteExpenseModal{{ $expense->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="deleteExpenseModalLabel">ব্যয় ডিলেট</h5>
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
      <!-- /.card -->
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
            @foreach($user->expenses->sortByDesc('created_at') as $expense)
            	<tr>
                <td style="line-height: 1;">
                    <span class="badge bg-primary">৳ {{ $expense->amount }}</span>
                    <span class="badge bg-success">{{ $expense->category->name }}</span>
                    <span class="badge bg-info">{{ $expense->qty }}</span><br/>
                    <small>
                        <span class="text-black-50">ব্যয় করেছেনঃ</span> {{ $expense->user->name }}<br/>
                        <small>{{ date('F d, Y h:i A', strtotime($expense->created_at)) }}</small>
                    </small> 
                </td>
            		<td align="right" width="40%">
                  @if($expense->image != null)
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#receiptExpenseModal{{ $expense->id }}">
                        <i class="fas fa-image"></i>
                    </button>
                  @endif
            			@if(Auth::user()->role == 'admin')
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteExpenseModal{{ $expense->id }}">
                          <i class="fas fa-trash-alt"></i>
                      </button>
                  @endif
            		</td>
                    
                    {{-- Expense Receipt Modal Code --}}
                    {{-- Expense Receipt Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="receiptExpenseModal{{ $expense->id }}" tabindex="-1" role="dialog" aria-labelledby="receiptExpenseModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="receiptExpenseModalLabel">ব্যয় রিসিট</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p style="width: 100%; overflow-y: scroll;">
                              @if($expense->image != null)
                                <img src="{{ asset('images/expenses/' . $expense->image) }}">
                              @endif
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    {{-- Expense Receipt Modal Code --}}
                    {{-- Expense Receipt Modal Code --}}

                    {{-- Delete Expense Modal Code --}}
                    {{-- Delete Expense Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="deleteExpenseModal{{ $expense->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="deleteExpenseModalLabel">ব্যয় ডিলেট</h5>
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
    {{-- {{ $expenses->links() }} --}}

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
            {{-- @foreach($monthlyexpenses as $monthlyexpense)
              <tr>
                <td>{{ date('F Y', strtotime($monthlyexpense->created_at)) }}</td>
                <td>৳ {{ $monthlyexpense->totalamount }}</td>
              </tr>
            @endforeach --}}
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>

    </div>
@endsection

@section('third_party_scripts')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript" src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
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

    jQuery(document).ready( function() {
          $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function(event, label) {
            
            var input = $(this).parents('.input-group').find(':text'),
                log = label;
            
            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
          
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function(){
            readURL(this);
        });   
      });
  </script>
@endsection