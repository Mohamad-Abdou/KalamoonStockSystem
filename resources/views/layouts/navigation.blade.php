<nav x-data="{ open: false }" class="bg-primary drop-shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-second-color" />
                    </a>
                </div>
                @can('viewAny', App\Models\Item::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
                            {{ __('إدارة المواد') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewAny', App\Models\ItemGroup::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('item_groups.index')" :active="request()->routeIs('item_groups.index')">
                            {{ __('إدارة المجموعات') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewAny', App\Models\AppConfiguration::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('app.configure')" :active="request()->routeIs('app.configure')">
                            {{ __('التحكم') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewAny', App\Models\User::class)
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        {{ __('إدارة المستخدمين') }}
                    </x-nav-link>
                </div>
                @endcan
                @can('create', App\Models\Stock::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('stock.insertQunatity')" :active="request()->routeIs('stock.insertQunatity')">
                            {{ __('إدخال كميات') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('stock.periodic-requests')" :active="request()->routeIs('stock.periodic-requests')">
                            {{ __('الطلبات الواردة') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewany', App\Models\AnnualRequest::class)
                    <div class="hidden space-x-8 sm:flex items-center sm:ms-10">
                        <x-dropdown width="48" class="">
                            <x-slot name="trigger">
                                <button
                                    class="items-center px-1 pt-1 border border-transparent text-sm leading-4 rounded-md text-white  hover:text-second-color focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ __('الطلبات السنوية') }}</div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('annual-request.index')">
                                    {{ __('الطلبات السنوية') }}
                                </x-dropdown-link>
                                @if (Auth::user()->is_part_of_the_annual_flow)
                                    <x-dropdown-link :href="route('annual-request-flow.index')">
                                        {{ __('الطلبات السنوية الواردة') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('annual-requests.archive')">
                                        {{ __('أرشيف الطلبات السنوية') }}
                                    </x-dropdown-link>
                                @endif
                                @can('resetYear', App\Models\AnnualRequest::class)
                                    <x-dropdown-link :href="route('annual-request.reset')">
                                        {{ __('تدوير السنة') }}
                                    </x-dropdown-link>
                                @endcan
                                @can('balancesManager', App\Models\AnnualRequest::class)
                                    <x-dropdown-link :href="route('annual-request.balanes')">
                                        {{ __('إدارة الأرصدة') }}
                                    </x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endcan
                @can('viewany', App\Models\PeriodicRequest::class)
                    <div class="hidden space-x-8 sm:flex items-center sm:ms-10">
                        <x-dropdown width="48" class="">
                            <x-slot name="trigger">
                                <button
                                    class="items-center px-1 pt-1 border border-transparent text-sm leading-4 rounded-md text-white  hover:text-second-color focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ __('الطلبات الدورية') }}</div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('periodic-request.index')">
                                    {{ __('طلب احتياج مادة') }}
                                </x-dropdown-link>
                                @if (Auth::user()->is_part_of_the_periodic_flow)
                                    <x-dropdown-link :href="route('periodic-request-flow.index')">
                                        {{ __('طلبات الاحتياج الواردة') }}
                                    </x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endcan
                @can('viewAny', App\Models\Stock::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('stocks.index')" :active="request()->routeIs('stocks.index')">
                            {{ __('حركة المواد') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('report', App\Models\Stock::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('stock.NeededReport')" :active="request()->routeIs('stock.NeededReport')">
                            {{ __('المستودع') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('viewAny', App\Models\BufferStock::class)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('buffer-stock.index')" :active="request()->routeIs('buffer-stock.index')">
                            {{ __('المخزون الاحتياطي') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('InsertionConfirmation', App\Models\Stock::class)
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('stock.insertionConfirmation')" :active="request()->routeIs('stock.insertionConfirmation')">
                        {{ __('توثيق دخل المستودع') }}
                    </x-nav-link>
                </div>
                @endcan
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->role }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('تسجيل الخروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
