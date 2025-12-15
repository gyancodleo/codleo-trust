@extends('admin.layouts.main')
@section('main-content')

<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="sales">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Data</span>
            </li>
        </ul>

        <div class="pt-5">

            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="mb-5 flex items-center">
                        <h5 class="text-lg font-semibold dark:text-white-light">
                            Total Policies <span class="block text-sm font-normal text-white-dark">Go to policies list for details.</span>
                        </h5>
                        <div class="relative ltr:ml-auto rtl:mr-auto">
                            <div class="grid h-11 w-11 place-content-center rounded-full bg-[#ffeccb] text-warning dark:bg-warning dark:text-[#ffeccb] text-lg font-semibold">
                                {{ App\models\policies::count() ?? '0' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="mb-5 flex items-center">
                        <h5 class="text-lg font-semibold dark:text-white-light">
                            Total Policies Categories<span class="block text-sm font-normal text-white-dark">Go to policies categories list for details.</span>
                        </h5>
                        <div class="relative ltr:ml-auto rtl:mr-auto">
                            <div class="grid h-11 w-11 place-content-center rounded-full bg-[#ffeccb] text-warning dark:bg-warning dark:text-[#ffeccb] text-lg font-semibold">
                                {{ App\models\policies_category::count() ?? '0' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full sm:col-span-2 xl:col-span-1">
                    <div class="mb-5 flex items-center">
                        <h5 class="text-lg font-semibold dark:text-white-light">
                            Total Clients Users <span class="block text-sm font-normal text-white-dark">Go to client Users list for details.</span>
                        </h5>
                        <div class="relative ltr:ml-auto rtl:mr-auto">
                            <div class="grid h-11 w-11 place-content-center rounded-full bg-[#ffeccb] text-warning dark:bg-warning dark:text-[#ffeccb] text-lg font-semibold">
                                {{ App\models\client_users::count() ?? '0' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main content section -->
</div>

@endsection