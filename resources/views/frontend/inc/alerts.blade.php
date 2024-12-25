<script>
    $(document).ready(function() {
        @if(Session::has('message'))
        toastr.options = {
            "closeButton": false,
            "progressBar": false,
        }
        toastr.success("{{ session('message') }}");
        @endif

        @if(Session::has('error'))
        toastr.options = {
            "closeButton": false,
            "progressBar": false,
        }
        toastr.error("{{ session('error') }}");
        @endif

        @if(Session::has('info'))
        toastr.options = {
            "closeButton": false,
            "progressBar": false,
        }
        toastr.info("{{ session('info') }}");
        @endif

        @if(Session::has('warning'))
        toastr.options = {
            "closeButton": false,
            "progressBar": false,
        }
        toastr.warning("{{ session('warning') }}");
        @endif
    });
</script>