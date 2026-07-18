{{-- ============================================================
    ERP SYSTEM
    E-Commerce Management System

    COMPONENT
    Announcement Bar

    STATUS
    Frontend Only

    ============================================================

    TODO: BACKEND

    ERP Integration

    Source Module:
    • Sales Management System
        - Promotional Announcements
        - Shipping Promotions

    • Authentication Module
        - Display "Admin Portal" based on user role

    • Company Information Module
        - Company Contact Number

============================================================ --}}

<section class="w-full bg-black text-white">

    <div
        class="mx-auto
               flex
               h-10
               max-w-[1440px]
               items-center
               justify-between
               px-5
               lg:px-10">

        {{-- ===============================================
            LEFT ANNOUNCEMENT

            TODO: ERP INTEGRATION

            Sales Management System

            Replace with active promotion.
        ================================================ --}}
        <div
            class="flex
                   items-center
                   gap-2
                   text-xs
                   font-normal">

            <span>
                Free on orders over ₱99
            </span>

            <span class="text-gray-500">
                |
            </span>

            <span>
                Next-Day Delivery Available
            </span>

        </div>

        {{-- ===============================================
            RIGHT LINKS

            TODO: ERP INTEGRATION

            Authentication Module

            Replace Admin Portal route.

            Company Information Module

            Replace contact number dynamically.
        ================================================ --}}
        <div
            class="flex
                   items-center
                   gap-3
                   text-xs">

            <a
                href="{{ route('admin.dashboard') }}"
                class="transition-colors duration-300 hover:text-red-500">

                Admin Portal

            </a>

            <span class="text-gray-500">
                |
            </span>

            <a
                href="tel:+639123456789"
                class="transition-colors duration-300 hover:text-red-500">

                09*********

            </a>

        </div>

    </div>

</section>
