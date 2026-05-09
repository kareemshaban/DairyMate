<div class="buy-now" hidden="hidden">
    <a
        href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
    >Upgrade to Pro</a
    >
</div>

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

<script src="{{asset('assets/vendor/js/menu.js')}}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

<!-- Main JS -->
<script src="{{asset('assets/js/main.js')}}"></script>

<!-- Page JS -->
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>


<!-- DataTables JS -->
<script src="{{asset('assets/js/datatables/dataTables.min.js')}}"></script>

<!-- DataTables Export Buttons -->
<script src="{{asset('assets/js/datatables/buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatables/html5.min.js')}}"></script>
<script src="{{asset('assets/js/datatables/print.min.js')}}"></script>

<!-- JSZip and PDFMake for export buttons -->
<script src="{{asset('assets/js/datatables/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatables/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/datatables/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/toastr.min.js')}}"></script>
<!-- Include SweetAlert2 via CDN if not already included -->
<script src="{{asset('assets/js/sweetalert.js')}}"></script>

<script>

    const choicesInstances = {};

        if($('.date').length){
            flatpickr(".date", {
                dateFormat: "d-m-Y", // dd-mm-yyyy
                defaultDate: new Date()
            });
        }
    if($('.date_time').length){
        flatpickr(".date_time", {
            enableTime: true,
            dateFormat: "d-m-Y H:i", // e.g. 03-05-2025 14:30
            time_24hr: true,
            defaultDate: new Date()
        });
    }

    if($('.search').length > 0){


        document.querySelectorAll('.search').forEach(function (element) {
            const id = element.getAttribute('id');
            choicesInstances[id] = new Choices(element, {
                searchEnabled: true
            });
        });
    }
    if($('.time').length){
        flatpickr(".time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i", // 24-hour format
            time_24hr: true,
            defaultDate: new Date()
        });
    }

</script>
@if(Config::get('app.locale')=='ar' )
    <script>

        $(document).ready(function() {
            $('table:not(.view_table)').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy' , 'excel',  'print'
                ],
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'

                }
            });
        });
    </script>
@else

    <script>
        $(document).ready(function() {
            $('table:not(.view_table)').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy' , 'excel', 'pdf', 'print'
                ],
                responsive: true,

            });
        });

    </script>
@endif

<script>

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };


    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
    @endif

    @if(Session::has('info'))
    toastr.info("{{ Session::get('info') }}");
    @endif

    @if(Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}");
    @endif

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    toastr.error("{{ $error }}");
    @endforeach

    @php
        session()->forget('errors');
    @endphp
@endif
</script>

<script>
    document.addEventListener('focusin', function (event) {
        if (event.target.matches('input[type="number"]')) {
            const input = event.target;

            if (input.value === '0' || input.value === '0.0' || input.value === '0.00') {
                input.dataset.wasZero = 'true';
                input.value = '';
            } else {
                input.dataset.wasZero = 'false';
            }
        }
    });

    document.addEventListener('focusout', function (event) {
        if (event.target.matches('input[type="number"]')) {
            const input = event.target;

            if (input.dataset.wasZero === 'true' && input.value.trim() === '') {
                input.value = '0';
            }
        }
    });
    
    document.addEventListener('keydown', function (event) {
    if (event.target.matches('input[type="number"]')) {
        if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
            event.preventDefault();
        }
    }
});

// منع تغيير القيمة عن طريق بكرة الماوس (اختياري ولكنه مهم جداً)
document.addEventListener('wheel', function (event) {
    if (document.activeElement.type === 'number') {
        document.activeElement.blur();
    }
}, { passive: false });

</script>


