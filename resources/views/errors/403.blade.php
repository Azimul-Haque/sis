@extends('layouts.app')
@section('title') 403 Forbidden @endsection

@section('content')
    <div class="container-fluid">
        @section('page-header') 403 Forbidden @endsection
		<div class="error-page">
			<h2 class="headline text-warning"> 403</h2>
			<div class="error-content">
				<h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! You are not allowed.</h3>

				<p>
					The page you are requesting is not allowed for you.
					Instead, you may <a href="{{ route('dashboard.index') }}">return to dashboard</a> or try using the search form.
				</p>

				<form class="search-form">
					<div class="input-group">
						<input type="text" name="search" class="form-control" placeholder="Search">

						<div class="input-group-append">
							<button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
							</button>
						</div>
					</div>
					<!-- /.input-group -->
				</form>
			</div>
			<!-- /.error-content -->
		</div>
    </div>
@endsection
