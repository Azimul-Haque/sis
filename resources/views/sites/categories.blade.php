@extends('layouts.app')
@section('title') ড্যাশবোর্ড | Categories @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') Categories @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">Categories</h3>

            <div class="card-tools">
        	    <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addCategoryModal">
        			<i class="fas fa-plus-square"></i> Add
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
                @foreach($categories as $category)
                	<tr>
                		<td>
                            {{ $category->name }}
                		</td>
                		<td align="right" width="40%">
                			<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editCategoryModal{{ $category->id }}">
                				<i class="fas fa-edit"></i>
                			</button>
            			    {{-- Edit Category Modal Code --}}
            			    {{-- Edit Category Modal Code --}}
            			    <!-- Modal -->
            			    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true" data-backdrop="static">
            			      <div class="modal-dialog" role="document">
            			        <div class="modal-content">
            			          <div class="modal-header bg-primary">
            			            <h5 class="modal-title" id="editCategoryModalLabel">Edit Site</h5>
            			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            			              <span aria-hidden="true">&times;</span>
            			            </button>
            			          </div>
            			          <form method="post" action="{{ route('dashboard.categories.update', $category->id) }}">
            				          <div class="modal-body">
            				            
            				                @csrf

            				                <div class="input-group mb-3">
                                                <input type="text"
                                                       name="name"
                                                       class="form-control"
                                                       value="{{ $category->name }}"
                                                       placeholder="Site Name" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><span class="fas fa-tag"></span></div>
                                                </div>
                                            </div>
                                                  				            
            				          </div>
            				          <div class="modal-footer">
            				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            				            <button type="submit" class="btn btn-primary">Update</button>
            				          </div>
            			          </form>
            			        </div>
            			      </div>
            			    </div>
            			    {{-- Edit Category Modal Code --}}
            			    {{-- Edit Category Modal Code --}}
                		</td>
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $categories->links() }}
    </div>


    {{-- Add Category Modal Code --}}
    {{-- Add Category Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.categories.store') }}">
	          <div class="modal-body">
	            
	                @csrf

	                <div class="input-group mb-3">
	                    <input type="text"
	                           name="name"
	                           class="form-control"
	                           value="{{ old('name') }}"
	                           placeholder="Category Name" required>
	                    <div class="input-group-append">
	                        <div class="input-group-text"><span class="fas fa-tag"></span></div>
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
    {{-- Add Category Modal Code --}}
    {{-- Add Category Modal Code --}}
@endsection

@section('third_party_scripts')

@endsection