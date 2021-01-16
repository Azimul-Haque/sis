@extends('layouts.app')
@section('title') ড্যাশবোর্ড @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') ড্যাশবোর্ড @endsection
    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h4>{{ $totalbalance - $totalexpense }}<sup style="font-size: 20px">৳</sup></h4>

                <p>বর্তমান ব্যালেন্স</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('dashboard.balance') }}" class="small-box-footer">ব্যালেন্স পাতা <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h4>{{ $totalexpense }}<sup style="font-size: 20px">৳</sup></h4>

                <p>মোট খরচ</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="{{ route('dashboard.expense.index') }}" class="small-box-footer">খরচ যোগ করুন <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h4>{{ $totalsites }}</h4>

                <p>মোট সাইট</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{ route('dashboard.sites') }}" class="small-box-footer">সাইটসমূহ <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h4>{{ $totalusers }}</h4>

                <p>মোট ব্যবহারকারী</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('dashboard.users') }}" class="small-box-footer">ব্যবহারকারীগণ <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
    </div>
@endsection

@section('third_party_scripts')

@endsection