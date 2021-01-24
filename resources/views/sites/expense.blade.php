@extends('layouts.app')
@section('title') ড্যাশবোর্ড | Add Expense @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') Add Expense @endsection
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="card card-success">
		          <div class="card-header">
		            <h3 class="card-title">Add Expense</h3>

		            <div class="card-tools">
		            	{{-- <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addExpenseModal">
		            		<i class="fas fa-hand-holding-usd"></i> Add
		            	</button> --}}
		            </div>
		          </div>
		          <!-- /.card-header -->
		          <div class="card-body">
	      	          <form method="post" action="{{ route('dashboard.expense.store') }}">
				          @csrf
				          
				          <div class="input-group mb-3">
				          	<select name="site_id" class="form-control" required>
				          		<option selected="" disabled="" value="">Select Site</option>
				          		@foreach($sites as $site)
				          			<option value="{{ $site->id }}">{{ $site->name }}</option>
				          		@endforeach
				          	</select>
				              <div class="input-group-append">
				                  <div class="input-group-text"><span class="fas fa-briefcase"></span></div>
				              </div>
				          </div>

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
				          <button type="submit" class="btn btn-success">Save</button>
			          </form>
		          </div>
		          <!-- /.card-body -->
		        </div>
			</div>
		</div>
    </div>
@endsection

@section('third_party_scripts')

@endsection