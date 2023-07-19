<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{url('/assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{url('/assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{url('/assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{url('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

<script src="{{url('/assets/vendor/js/menu.js')}}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{url('/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

<!-- Main JS -->
<script src="{{url('/assets/js/main.js')}}"></script>

{{--Toast JS--}}
<script src="{{url('/assets/js/ui-toasts.js')}}"></script>

{{--Sweet Alert--}}
<!-- Page JS -->
<script src="{{url('/assets/js/dashboards-analytics.js')}}"></script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>


<script type="module" src="{{url('/js/notifications.js')}}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

