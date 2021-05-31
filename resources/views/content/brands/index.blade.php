
@extends('layouts/contentLayoutMaster')

@section('title', 'Brands')

@section('content')

<!-- Basic table -->
<section id="brands">
  <div class="row">
      <div class="col-12">
          <brands-list></brands-list>
      </div>
  </div>
  <add-brand></add-brand>
</section>
<!--/ Basic table -->
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script type="text/javascript">
    var base_url = "{{ url('/'). '/' }}";
  </script>
  <script src="{{ asset('js/brands.js') }}"></script>
  
@endsection
