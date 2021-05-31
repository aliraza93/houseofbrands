
@extends('layouts/contentLayoutMaster')

@section('title', 'Vendors')

@section('content')

<!-- Basic table -->
<section id="vendors">
  <div class="row">
      <div class="col-12">
          <vendors-list></vendors-list>
      </div>
  </div>
  <add-vendor></add-vendor>
</section>
<!--/ Basic table -->
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script type="text/javascript">
    var base_url = "{{ url('/'). '/' }}";
  </script>
  <script src="{{ asset('js/vendors.js') }}"></script>
  
@endsection
