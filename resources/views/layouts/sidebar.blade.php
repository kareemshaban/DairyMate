<style>
    @media (min-width: 1200px) {
        /* لما المنيو تصغر، خلي عرضها مثلاً 80 بكسل */
        html.layout-menu-collapsed .layout-menu {
            width: 84px !important;
            transition: all 0.3s ease;
        }

        /* اخفاء النصوص وسهم القوائم المنسدلة لما تكون المنيو صغيرة */
        html.layout-menu-collapsed .menu-inner > .menu-item > .menu-link > div,
        html.layout-menu-collapsed .menu-inner > .menu-item > .menu-link::after,
        html.layout-menu-collapsed .menu-header {
            display: none;
        }

        /* توسيع الجزء الخاص بالمحتوى ليعوض النقص في عرض المنيو */
        html.layout-menu-collapsed .layout-page {
            padding-left: 84px !important; /* لو الموقع انجليزي */
        }

        /* تعديل المسافات للعربي RTL */
        html[dir="rtl"].layout-menu-collapsed .layout-page {
            padding-right: 84px !important;
            padding-left: 0 !important;
        }

        /* شكل السهم في وضع الـ Collapsed */
        html.layout-menu-collapsed #toggle-sidebar i {
            transform: rotate(180deg);
        }
    }
</style>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" >
    <div class="app-brand demo">
        <a href="{{route('index')}}" class="app-brand-link">
              <span class="app-brand-logo demo">
                  <img  src="{{asset('assets/img/logo_side.png')}}" style="height: 60px" />
              </span>

        </a>
     

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item @if($slag == 0)  active @endif">
            <a href="{{route('index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">{{__('main.dashboard')}}</div>
            </a>
        </li>


        <li class="menu-item @if($slag == 1)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">{{__('main.basicData')}}</div>
            </a>

            <ul class="menu-sub">
                @can('page-access', [1, 'view'])
                    <li class="menu-item @if($subSlag == 11)  active @endif">
                        <a href="{{route('stores')}}" class="menu-link">
                            <div data-i18n="Without menu">{{__('main.stores')}}</div>
                        </a>
                    </li>
                @endcan
                @can('page-access', [2, 'view'])
                    <li class="menu-item @if($subSlag == 12)  active @endif">
                        <a href="{{route('safes')}}" class="menu-link">
                            <div data-i18n="Without navbar">{{__('main.safes')}}</div>
                        </a>
                    </li>
                @endcan
                @can('page-access', [3, 'view'])
                    <li class="menu-item @if($subSlag == 13)  active @endif">
                        <a href="{{route('expenses_types')}}" class="menu-link">
                            <div data-i18n="Without navbar">{{__('main.recipit_type')}}</div>
                        </a>
                    </li>
                @endcan

            </ul>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{__('main.product_department')}}</span></li>
        <!-- Cards -->

        <!-- User interface -->
        <li class="menu-item @if($slag == 4)  active @endif">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="User interface">{{__('main.product_list')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [4, 'view'])
                    <li class="menu-item @if($subSlag == 44)  active @endif">
                        <a href="{{route('items')}}" class="menu-link">
                            <div data-i18n="Buttons">{{__('main.products')}}</div>
                        </a>
                    </li>

                    <li class="menu-item @if($subSlag == 46)  active @endif">
                        <a href="{{route('items-quantity')}}" class="menu-link">
                            <div data-i18n="Buttons">{{__('main.items_qnt')}}</div>
                        </a>
                    </li>
                @endcan


            </ul>
        </li>

        <li class="menu-header small text-uppercase" hidden="hidden"><span class="menu-header-text">{{__('main.cars_department')}}</span></li>

        <li class="menu-item @if($slag == 5)  active @endif">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div data-i18n="Extended UI">{{__('main.cars_list')}}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if($subSlag == 51)  active @endif">
                    <a href="{{route('cars')}}" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">{{__('main.cars_list')}}</div>
                    </a>
                </li>

            </ul>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{__('main.factory_department')}}</span>
        </li>
        <li class="menu-item @if($slag == 2)  active @endif" >
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Account Settings">{{__('main.suppliers')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [7, 'view'])
                    <li class="menu-item @if($subSlag == 21)  active @endif">
                        <a href="{{route('suppliers' , 1)}}"  class="menu-link">
                            <div data-i18n="Account">{{__('main.suppliers_list')}}</div>
                        </a>
                    </li>
                @endcan

                @can('page-access', [7, 'view'])
                    <li class="menu-item @if($subSlag == 22)  active @endif">
                        <a href="{{route('suppliers' , 0)}}"  class="menu-link">
                            <div data-i18n="Account">{{__('main.clients')}}</div>
                        </a>
                    </li>
                @endcan

                <li class="menu-item @if($subSlag == 23)  active @endif">
                    <a href="{{route('insuranceBalances')}}"  class="menu-link">
                        <div data-i18n="Account">{{__('main.supplier_insurance_balance')}}</div>
                    </a>
                </li>

            </ul>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{__('main.milk_department')}}</span>
        </li>
        <li class="menu-item @if($slag == 3)  active @endif" >
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Account Settings">{{__('main.milk_meals')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [8, 'view'])
                    <li class="menu-item @if($subSlag == 31)  active @endif">
                        <a href="{{route('milk_meals')}}"  class="menu-link">
                            <div data-i18n="Account">{{__('main.milk_meals')}}</div>
                        </a>
                    </li>

                    <li class="menu-item @if($subSlag == 32)  active @endif">
                        <a href="{{route('posted_milk_meals')}}"  class="menu-link">
                            <div data-i18n="Account">{{__('main.milk_meals_posted')}}</div>
                        </a>
                    </li>

                @endcan


            </ul>
        </li>
        <li class="menu-item @if($slag == 3)  active @endif" hidden="hidden">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Account Settings">{{__('main.weakly_meals')}}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if($subSlag == 31)  active @endif">
                    <a href="{{route('weakly_meals')}}"  class="menu-link">
                        <div data-i18n="Account">{{__('main.weakly_meals_list')}}</div>
                    </a>
                </li>
                <li class="menu-item @if($subSlag == 32)  active @endif">
                    <a href="#"  class="menu-link">
                        <div data-i18n="Account">{{__('main.car_meal_check')}}</div>
                    </a>
                </li>


            </ul>
        </li>
        <li class="menu-item @if($slag == 6)  active @endif" hidden="hidden">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements">{{__('main.daily_meals')}}</div>
            </a>
            <ul class="menu-sub" >
                <li class="menu-item @if($subSlag == 61)  active @endif">
                    <a href="{{route('daily_meals')}}" class="menu-link">
                        <div data-i18n="Basic Inputs">{{__('main.daily_meals_list')}}</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Components -->


        <!-- Extended components -->




        <!-- Forms & Tables -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{__('main.cheese_department')}}</span></li>
        <!-- Forms -->

        <li class="menu-item @if($slag == 7)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">{{__('main.cheese_meals')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [10, 'view'])
                    <li class="menu-item @if($subSlag == 71)  active @endif">
                        <a href="{{route('cheese-meals')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.cheese_meals_list')}}</div>
                        </a>
                    </li>
                    <li class="menu-item @if($subSlag == 73)  active @endif">
                        <a href="{{route('cheese-meals-posted')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.posted_cheese_meals')}}</div>
                        </a>
                    </li>


                    <li class="menu-item @if($subSlag == 72)  active @endif">
                        <a href="{{route('cheese_meal_step1')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.cheese_meals_create')}}</div>
                        </a>
                    </li>
                @endcan

            </ul>
        </li>

        <li class="menu-item @if($slag == 17)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">{{__('main.item_transform_docs')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [10, 'view'])
                    <li class="menu-item @if($subSlag == 171)  active @endif">
                        <a href="{{route('item_transform_docs')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.item_transform_docs')}}</div>
                        </a>
                    </li>
                @endcan

            </ul>
        </li>


        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{__('main.accounting_department')}}</span></li>
        <!-- Forms -->
        <li class="menu-item @if($slag == 8)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements">{{__('main.sales')}}</div>
            </a>
            <ul class="menu-sub">

                @can('page-access', [11, 'view'])
                    <li class="menu-item  @if($subSlag == 81)  active @endif">
                        <a href="{{route('sales')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.sales_list')}}</div>
                        </a>
                    </li>
                    <li class="menu-item  @if($subSlag == 82)  active @endif">
                        <a href="{{route('sales_create')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.add_sales')}}</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
        <li class="menu-item  @if($slag == 9)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">{{__('main.stock_bonds')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [13, 'view'])
                    <li class="menu-item @if($subSlag == 91)  active @endif">
                        <a href="{{route('stock_exchange')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.stock_exchange_list')}}</div>
                        </a>
                    </li>
                    <li class="menu-item @if($subSlag == 92)  active @endif">
                        <a href="{{route('stock_exchange_create')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.stock_exchange_add')}}</div>
                        </a>
                    </li>
                @endcan
                @can('page-access', [18, 'view'])
                    <li class="menu-item @if($subSlag == 93)  active @endif">
                        <a href="{{route('stock_in')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.stock_in_list')}}</div>
                        </a>
                    </li>
                @endcan


            </ul>
        </li>
        <li class="menu-item  @if($slag == 10)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">{{__('main.recipits')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [15, 'view'])
                    <li class="menu-item @if($subSlag == 101)  active @endif">
                        <a href="{{route('recipits')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.recipit_list')}}</div>
                        </a>
                    </li>
                @endcan


            </ul>
        </li>
        <li class="menu-item  @if($slag == 11)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">{{__('main.catches')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [16, 'view'])
                    <li class="menu-item @if($subSlag == 111)  active @endif">
                        <a href="{{route('catches')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.catches_list')}}</div>
                        </a>
                    </li>
                @endcan

            </ul>
        </li>
        <li class="menu-item  @if($slag == 12)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">{{__('main.box_recipit')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [17, 'view'])
                    <li class="menu-item @if($subSlag == 121)  active @endif">
                        <a href="{{route('boxRecipits')}}" class="menu-link">
                            <div data-i18n="Vertical Form">{{__('main.box_recipit_list')}}</div>
                        </a>
                    </li>
                @endcan


            </ul>
        </li>
        <li class="menu-item  @if($slag == 13)  active @endif" >
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">{{__('main.balance_transactions')}}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if($subSlag == 131)  active @endif">
                    <a href="{{route('balance_transactions')}}" class="menu-link">
                        <div data-i18n="Vertical Form">{{__('main.balance_transactions')}}</div>
                    </a>
                </li>


            </ul>
        </li>


        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{__('main.employee_department')}}</span></li>
        <li class="menu-item @if($slag == 18)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements">{{__('main.employee_department')}}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item  @if($subSlag == 181)  active @endif">
                    <a href="{{route('employees')}}" class="menu-link">
                        <div data-i18n="Basic Inputs">{{__('main.employees')}}</div>
                    </a>
                </li>
                <li class="menu-item  @if($subSlag == 182)  active @endif">
                    <a href="{{route('attendances')}}" class="menu-link">
                        <div data-i18n="Basic Inputs">{{__('main.attendances')}}</div>
                    </a>
                </li>
                <li class="menu-item  @if($subSlag == 183)  active @endif">
                    <a href="{{route('advances')}}" class="menu-link">
                        <div data-i18n="Basic Inputs">{{__('main.advances')}}</div>
                    </a>
                </li>
                <li class="menu-item  @if($subSlag == 184)  active @endif" hidden="hidden">
                    <a href="{{route('salaries')}}" class="menu-link">
                        <div data-i18n="Basic Inputs">{{__('main.salaries')}}</div>
                    </a>
                </li>




            </ul>
        </li>


        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{__('main.reports_department')}}</span></li>
        <!-- Forms -->
        <li class="menu-item @if($slag == 14)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements">{{__('main.report_list')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [19, 'view'])
                    <li class="menu-item @if($subSlag == 141)  active @endif">
                        <a href="{{route('clientAccountSearch')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.client_account_report')}}</div>
                        </a>
                    </li>
                    <li class="menu-item @if($subSlag == 142)  active @endif">
                        <a href="{{route('stockMovementSearch')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.stock_movement_report')}}</div>
                        </a>
                    </li>
                    <li class="menu-item @if($subSlag == 144)  active @endif">
                        <a href="{{route('stockMealMovementSearch')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.stock_report_by_meal')}}</div>
                        </a>
                    </li>
                    <li class="menu-item @if($subSlag == 143)  active @endif">
                        <a href="{{route('safeMovementSearch')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.safe_movement_report')}}</div>
                        </a>
                    </li>

                    <li class="menu-item @if($subSlag == 145)  active @endif" hidden="hidden">
                        <a href="{{route('dailyMealsSearch')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.dailyMealsReport')}}</div>
                        </a>
                    </li>

                    <li class="menu-item @if($subSlag == 146)  active @endif" hidden="hidden">
                        <a href="{{route('weaklyMealsReport')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.weaklyMealsReport')}}</div>
                        </a>
                    </li>

                    <li class="menu-item @if($subSlag == 147)  active @endif" hidden="hidden">
                        <a href="{{route('cheeseMealsSearch')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.cheeseMealsReport')}}</div>
                        </a>
                    </li>

                    <li class="menu-item @if($subSlag == 148)  active @endif" hidden="hidden">
                        <a href="{{route('vehicleMealsSearch')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.vehicleMealsReport')}}</div>
                        </a>
                    </li>
                @endcan


            </ul>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{__('main.users_and_auth_department')}}</span></li>
        <li class="menu-item @if($slag == 16)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Form Elements">{{__('main.settings')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [23, 'view'])
                    <li class="menu-item @if($subSlag == 161)  active @endif">
                        <a href="{{route('settings')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.settings')}}</div>
                        </a>
                    </li>
                @endcan

            </ul>
        </li>

        <!-- Misc -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">{{__('main.users_and_auth_department')}}</span></li>
        <li class="menu-item @if($slag == 15)  active @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements">{{__('main.users_list')}}</div>
            </a>
            <ul class="menu-sub">
                @can('page-access', [20, 'view'])
                    <li class="menu-item @if($subSlag == 151)  active @endif">
                        <a href="{{route('users')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.users')}}</div>
                        </a>
                    </li>
                @endcan
                @can('page-access', [21, 'view'])
                    <li class="menu-item @if($subSlag == 152)  active @endif">
                        <a href="{{route('roles')}}" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.roles')}}</div>
                        </a>
                    </li>
                @endcan
                @can('page-access', [22, 'view'])
                    <li class="menu-item">
                        <a href="{{route('auth')}} @if($subSlag == 153)  active @endif" class="menu-link">
                            <div data-i18n="Basic Inputs">{{__('main.auth')}}</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </li>

    </ul>
</aside>


