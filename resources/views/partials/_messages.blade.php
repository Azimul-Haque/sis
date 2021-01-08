<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script type="text/javascript">
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
</script>
   
@if (Session::has('success'))
  <script type="text/javascript">
    Toast.fire({
      icon: 'success',
      title: '{{Session::get('success')}}'
    })
  </script>
@endif


@if (count($errors) > 0)
  @foreach ($errors->all() as $error)
    <script type="text/javascript">
      Toast.fire({
        icon: 'error',
        title: '{{ $error }}'
      })
    </script>
  @endforeach 
@endif

@if(session('info'))
  <script type="text/javascript">
    Toast.fire({
      icon: 'info',
      title: '{{ session('info') }}'
    })
  </script>
@endif

@if(session('warning'))
    <script type="text/javascript">
    if($(window).width() > 768) {
      toastr.warning('{{ session('warning') }}', 'WARNING').css('width', '400px');
    } else {
      toastr.warning('{{ session('warning') }}', 'WARNING').css('width', ($(window).width()-25)+'px');
    }
  </script>
@endif