<!DOCTYPE html>

@include('layouts.head')

<style>
    .week-square {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 1rem;
        margin: 0.5rem;
        text-align: center;
        height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #f8f9fa;
        box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
        cursor: pointer;
    }
    .week-title {
        font-weight: 600;
        font-size: 1.1rem;
    }
    .week-subtitle {
        font-size: 0.9rem;
        color: #555;
        margin-top: 0.3rem;
    }
    .week-state {
        width: fit-content;
        display: block;
        margin: 10px auto;
        padding-left: 15px;
        padding-right: 15px;
    }
</style>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        @include('layouts.sidebar' , ['slag' => 5 , 'subSlag' => 52])
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('layouts.nav')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div style="display: flex ; justify-content: space-between ; align-items: center">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">{{__('main.cars_department')}} /</span> {{__('main.cars_meals')}}
                        </h4>



                    </div>



                    <!-- Responsive Table -->
                    <div class="card">
                        <h5 class="card-header">{{__('main.cars_meals')}}</h5>
                        @include('flash-message')
                        <div class="card-content" style="padding-right: 20px ; padding-left: 20px ; padding-bottom: 20px">
                            <div class="container py-4">
                                <div class="row">
                                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('main.year') }}  </label>
                                            <select  name="year" id="year"  @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                            class="form-control"  autofocus  required>

                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('main.month') }}  </label>
                                            <select  name="month" id="month"  @if(Config::get('app.locale')=='ar' ) dir="rtl" @endif
                                            class="form-control"  autofocus  required>

                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <div id="weeksGrid" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3" style="margin-top: 25px">
                                    <!-- Week squares go here -->
                                </div>
                            </div>



                        </div>

                    </div>
                    <!--/ Responsive Table -->
                </div>
                <!-- / Content -->

                <!-- Footer -->
                @include('layouts.footer_design')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>

@include('layouts.footer')
<div class="content-backdrop fade"></div>

<div id="loading-overlay">
    <div class="loader"></div>
    <div class="loading-text">{{__('main.month_loading')}}</div>
</div>
<script type="text/javascript">

    const dayNames = {
        1: "{{ __('main.day1') }}",
        2: "{{ __('main.day2') }}",
        3: "{{ __('main.day3') }}",
        4: "{{ __('main.day4') }}",
        5: "{{ __('main.day5') }}",
        6: "{{ __('main.day6') }}",
        7: "{{ __('main.day7') }}",
    };
    $(document).ready(function() {

        const monthNames = {
            1: "{{ __('main.month1') }}",
            2: "{{ __('main.month2') }}",
            3: "{{ __('main.month3') }}",
            4: "{{ __('main.month4') }}",
            5: "{{ __('main.month5') }}",
            6: "{{ __('main.month6') }}",
            7: "{{ __('main.month7') }}",
            8: "{{ __('main.month8') }}",
            9: "{{ __('main.month9') }}",
            10: "{{ __('main.month10') }}",
            11: "{{ __('main.month11') }}",
            12: "{{ __('main.month12') }}"
        };

        const yearSelect = document.getElementById('year');
        const currentYear = new Date().getFullYear();
        for(let y = 2020; y <= currentYear + 5; y++) {
            const option = document.createElement('option');
            option.value = y;
            option.textContent = y;
            if(y === currentYear) option.selected = true;
            yearSelect.appendChild(option);
        }

        const monthSelect = document.getElementById('month');

        const currentMonth = new Date().getMonth() + 1;
        Object.keys(monthNames).forEach(key => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = monthNames[key];

            if (parseInt(key) === currentMonth) option.selected = true;
            monthSelect.appendChild(option);
        });


        const choicesInstance = new Choices(monthSelect, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false
        });
        const choicesInstance2 = new Choices(yearSelect, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false
        });


        renderWeeksGrid(parseInt(yearSelect.value), parseInt(monthSelect.value));

        yearSelect.addEventListener('change', () => {
            renderWeeksGrid(parseInt(yearSelect.value), parseInt(monthSelect.value));
        });
        monthSelect.addEventListener('change', () => {
            renderWeeksGrid(parseInt(yearSelect.value), parseInt(monthSelect.value));
        });


        $(document).on('click', '.week-square', function() {
            const dateStr = $(this).attr('id');
            const week = $(this).data('week');   // meal_id

            const date = new Date(dateStr);

            const day = date.getDate(); // 30
            const month = date.getMonth() + 1; // 5 (Months are 0-based, so +1)
            const year = date.getFullYear();

            let url = "{{ route('cheese_meal_step2', ':id') }}";

            url = url.replace(':id', week);

            document.location.href=url;



        });

    });

    function formatDate(date) {
        // Format as YYYY-MM-DD
        const d = date.getDate().toString().padStart(2, '0');
        const m = (date.getMonth() + 1).toString().padStart(2, '0');
        const y = date.getFullYear();
        return `${y}-${m}-${d}`;
    }

    function getWeekStartFriday(date) {
        // Given any date, get the Friday of that week (start day)
        // JS: 0=Sun,...,5=Fri,6=Sat
        // If date is Friday (5), return date itself
        // else go forward/backward to Friday
        const day = date.getDay();
        // Calculate offset to previous Friday
        const diff = (day >= 5) ? day - 5 : 7 - (5 - day);
        const friday = new Date(date);
        friday.setDate(date.getDate() - diff);
        return friday;
    }

    function getWeeks(year, month) {
        // month is 1-based (1=January, 12=December)

        const weeks = [];

        // Create date at start of month
        let current = new Date(year, month - 1, 1);

        // Find the first Friday on or before the first day of month
        const dayOfWeek = current.getDay(); // Sunday=0 ... Friday=5
        const daysBackToFriday = (dayOfWeek >= 5) ? dayOfWeek - 5 : dayOfWeek + 2;
        current.setDate(current.getDate() - daysBackToFriday);

        while (true) {
            // Start date of week
            let weekStart = new Date(current);
            // End date of week = 6 days after start (Friday -> Thursday)
            let weekEnd = new Date(current);
            weekEnd.setDate(weekEnd.getDate() + 6);

            // Push this week to array
            weeks.push({start: weekStart, end: weekEnd});

            // Move to next week
            current.setDate(current.getDate() + 7);

            // Stop if the start of the week is past the month
            if (current.getMonth() > month - 1 || current.getFullYear() > year) break;
        }

        return weeks;
    }

    function renderWeeksGrid(year, month) {
        const weeksGrid = document.getElementById('weeksGrid');
        weeksGrid.innerHTML = '';

        const weeks = getWeeks(year, month);
        let windex = 0 ;
        weeks.forEach(week => {
            windex ++ ;
            const startDayName = dayNames[week.start.getDay() + 1];
            const endDayName = dayNames[week.end.getDay() + 1];



            const startDateStr = formatDate(week.start);
            const endDateStr = formatDate(week.end);

            // Create card div
            const colDiv = document.createElement('div');
            colDiv.className = 'col';

            const cardDiv = document.createElement('div');
            cardDiv.className = 'week-square';

            cardDiv.id = startDateStr ;
            // Title
            const title = document.createElement('div');
            title.className = 'week-title';
            title.textContent = `${startDayName} - ${endDayName} (${windex}) ` ;

            // Subtitle
            const subtitle = document.createElement('div');
            subtitle.className = 'week-subtitle';
            subtitle.textContent = `${startDateStr} - ${endDateStr}`;

            cardDiv.appendChild(title);
            cardDiv.appendChild(subtitle);
            colDiv.appendChild(cardDiv);
            weeksGrid.appendChild(colDiv);


            getMeals(week.start.getMonth() + 1 , week.start.getFullYear() ,week.start.getDate().toString().padStart(2, '0') , startDateStr);
        });
    }

     function getMeals(month, year, day, id) {
        // const overlay = document.getElementById('loading-overlay');
        //
        // // Show loading
        // overlay.style.display = 'flex';
        // const minDuration = 2000;
        // const startTime = Date.now();

        $.ajax({
            type: 'GET',
            url: `/getWeakMealsForCars/${month}/${year}/${day}`,
            dataType: 'json',

            success: function (meals) {
                console.log(meals);

                // Clear old badge if exists
                const container = document.getElementById(id);
                const existing = container.querySelector('.week-state');
                if (existing) existing.remove();

                const span = document.createElement('span');
                span.classList.add('badge', 'week-state');

                if (meals.length > 0) {
                    if (meals[0].isManufactured == 0) {
                    //    span.classList.add('bg-success');
                     //   span.textContent = "{{ __('main.isManufactured0') }}";
                    } else if(meals[0].isManufactured == 1) {
                      //  span.classList.add('bg-danger');
                      //  span.textContent = "{{ __('main.isManufactured1') }}";

                    }
                    else if(meals[0].isManufactured == 2) {
                       // span.classList.add('bg-primary');
                     //   span.textContent = "{{ __('main.isManufactured2') }}";

                    }
                    container.setAttribute('data-week', meals[0].id);
                } else {
                  //  span.classList.add('bg-warning');
                  //  span.textContent = "{{ __('main.mealState-1') }}";
                    container.setAttribute('data-week', 0);
                     const colDiv = container.closest('.col');
                    if (colDiv) {
                        colDiv.style.display = 'none';
                    }
                }

                container.appendChild(span);
            },

            error: function (xhr, status, error) {
                console.error('Failed to fetch meals:', error);
                toastr.error('حدث خطأ أثناء تحميل بيانات الوجبات.');
            },

            complete: function () {
                // const elapsed = Date.now() - startTime;
                // const remaining = minDuration - elapsed;
                //
                // setTimeout(() => {
                //     overlay.style.display = 'none';
                // }, Math.max(remaining, 0));
            }
        });
    }

</script>
</body>
</html>
