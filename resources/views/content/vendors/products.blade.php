
@extends('layouts/contentLayoutMaster')

@section('title', 'Vendor Products')

@section('content')

<!-- Basic table -->
<section id="vendors">
  <div class="row">
      <div class="col-12">
          <vendor-products-list :vendor="{{ $vendor }}"></vendor-products-list>
      </div>
  </div>
  <add-vendor-product :vendor="{{ $vendor }}"></add-vendor-product>
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
