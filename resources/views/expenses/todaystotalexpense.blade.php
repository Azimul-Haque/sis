@extends('layouts.app')
@section('title') ড্যাশবোর্ড | আজকের খরচ @endsection

@section('third_party_stylesheets')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">

@endsection

@section('content')
  @section('page-header') আজকের খরচ 
    <span style="font-size: 13px;">
      @if(!empty($transactiondate)) {{ bangla(date('F d, Y', strtotime($transactiondate))) }} @else {{ bangla(date('F d, Y')) }} @endif
    </span> 
  @endsection
    <div class="container-fluid">
      <div class="form-row">
        <div class="form-group col-xs-8">
          <input class="form-control" type="text" name="transactiondate" id="transactiondate" @if(!empty($transactiondate)) value="{{ date('F d, Y', strtotime($transactiondate)) }}" @else value="{{ date('F d, Y') }}" @endif placeholder="Select Date" readonly="">
        </div>
        <div class="form-group col-xs-4">
          <button id="loaddailyOtherAmounts" class="btn btn-success"><i class="fa fa fa-search"></i> দেখুন</button>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">আজকের মোট খরচঃ <b>৳ {{ $todaystotalexpense->totalamount }}</b></h3>

          <div class="card-tools">
            {{-- <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addBalanceModal">
              <i class="fas fa-coins"></i> অর্থ যোগ
            </button> --}}
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
             {{--  <tr>
                <th>ব্যয় করেছেন</th>
                <th>সাইট</th>
                <th>খাত</th>
                <th>পরিমাণ</th>
                <th>ব্য্যের পরিমাণ</th>
                <th>ব্য্যের পরিমাণ</th>
                
              </tr> --}}
              @foreach($expenses as $expense)
                <tr>
                  <td style="line-height: 1;">
                    <span class="badge bg-primary">ব্যয়ঃ ৳ {{ $expense->amount }}</span>
                    <span class="badge bg-success">খাতঃ {{ $expense->category->name }}</span>
                    <span class="badge bg-info">পরিমাণঃ {{ $expense->qty }}</span><br/>
                    <small>
                        <span class="text-black-50">ব্যয় করেছেনঃ</span> {{ $expense->user->name }},
                        <span class="badge bg-warning"><big>সাইটঃ {{ $expense->site->name }}</big></span><br/>
                        <small>{{ date('F d, Y h:i A', strtotime($expense->created_at)) }}</small>
                    </small> 
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      {{ $expenses->links() }}
    </div>

    
@endsection

@section('third_party_scripts')
  <script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script>
    $("#transactiondate").datepicker({
      format: 'MM dd, yyyy',
      todayHighlight: true,
      autoclose: true,
    });

    $('#loaddailyOtherAmounts').click(function() {
      var transactiondate = $('#transactiondate').val();

      if(isEmptyOrSpaces(transactiondate)) {
        if($(window).width() > 768) {
          toastr.warning('Select Date!', 'WARNING').css('width', '400px');
        } else {
          toastr.warning('Select Date!', 'WARNING').css('width', ($(window).width()-25)+'px');
        }
      } else {
        window.location.href = '/dashboard/expenses/'+ moment(transactiondate).format('YYYY-MM-DD');
      }
    });

    // on enter search
    function isEmptyOrSpaces(str){
        return str === null || str.match(/^ *$/) !== null;
    }
  </script>
@endsection