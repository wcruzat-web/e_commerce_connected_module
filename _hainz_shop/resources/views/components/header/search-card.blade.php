{{--
    ERP MODULE: Header — Search Card Component
    COMPONENT: SearchCard
    DESCRIPTION: Full-width search panel with categories, trending products. Toggled by search icon in header. Data flows from shop page.
    TODO: Wire search input to backend search endpoint
--}}
<section class="w-full">
    <div class="mx-auto max-w-[1280px] px-5 lg:px-8">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <!-- LEFT TEXT -->
                <div>
                    <h2 class="text-[26px] font-semibold text-gray-900">
                        Track Your Order
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Enter your order number or tracking number to view the latest shipment updates.
                    </p>
                </div>

                <!-- SEARCH FORM -->
                <form
                    action="#"
                    method="GET"
                    class="flex w-full lg:w-auto items-center gap-3"
                >

                    <!-- INPUT WRAPPER -->
                    <div class="relative w-full lg:w-[420px]">

                        <input
                            type="text"
                            name="query"
                            placeholder="Enter Order ID or Tracking Number"
                            class="h-14 w-full rounded-xl border border-gray-300 pl-5 pr-12 text-[15px]
                                   outline-none transition-all duration-300
                                   focus:border-red-600 focus:ring-2 focus:ring-red-200"
                        />

                        <!-- SEARCH ICON -->
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                    </div>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="h-14 px-8 rounded-xl bg-red-600 text-white font-medium
                               hover:bg-red-700 transition-all duration-300
                               active:scale-95 whitespace-nowrap"
                    >
                        Track
                    </button>

                </form>

            </div>

        </div>

    </div>
</section>
