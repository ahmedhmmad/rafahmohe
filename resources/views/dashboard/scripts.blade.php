<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{url('/js/select2.full.min.js')}}"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script src="{{url('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

<script src="{{url('/assets/vendor/js/menu.js')}}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{url('/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

<!-- Main JS -->
<script src="{{url('/assets/js/main.js')}}"></script>

{{--Toast JS--}}
{{--<script src="{{url('/assets/js/ui-toasts.js')}}"></script>--}}
<script>
    $(document).ready(function () {
        // Check if the success message exists in the session
        const successMessage = '{{ session('success') }}';
        if (successMessage) {
            // Show the toast when the page loads with the success message
            $('.toast-body').text(successMessage);
            $('.toast').toast('show');
        }
    });
</script>

{{--Sweet Alert--}}
<!-- Page JS -->
<script src="{{url('/assets/js/dashboards-analytics.js')}}"></script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>


<script type="module" src="{{url('/js/notifications.js')}}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script src="{{url('/js/printout.min.js')}}"></script>
<script>
    function printTable() {
        printout('table', {
            pageTitle: 'الخطة الشهرية',
            printContainer:true,
            header:null,
            footer:null,

        });
    }
</script>

<script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2(
            {
                theme: "classic",
                width: 'resolve',
                dir: "rtl",
                placeholder: "اختر",
                allowClear: true,

            }
        );
    });
    $(document).ready(function() {
        $('.js-example-basic-single').select2(
            {
                theme: "classic",

                dir: "rtl",
                placeholder: "اختر",
                allowClear: true,

            }
        );
    });
</script>



@stack('scripts')

