@extends('layouts.app')

@section('content')
<div class="jumbotron jumbotron-fluid text-center">
    <div class="container">
        <h1 class="display-4">Vyhľadať v databáze obcí</h1>
        <!-- Autocomplete Form -->
        <form id="searchForm" action="{{ route('city.search') }}" method="POST" class="mt-4">
            @csrf
            <input type="text" id="citySearch" name="city" class="form-control form-control-lg" placeholder="Zadajte názov" required>
        </form>

        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>

<!-- Include jQuery, jQuery UI, and custom script for autocomplete -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    $(document).ready(function() {
        $("#citySearch").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete') }}",  // AJAX route for autocomplete suggestions
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                // Redirect to the selected city's detail page
                window.location.href = '/city/' + ui.item.id;
            },
            minLength: 2,  // Minimum characters before suggestions appear
        });
    });
</script>
@endsection
