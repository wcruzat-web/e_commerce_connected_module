{{--
    ERP MODULE: Authentication — Branding Panel (Auth Pages)
    COMPONENT: AuthBranding
    DESCRIPTION: Left-side branding panel shown on login and register pages.
    DATA SOURCE: $heading, $highlight, $buttonText passed from parent view
--}}

<div>
    <h2 class="text-3xl font-extrabold text-gray-900 leading-tight mb-4">
        {{ $heading }} <span class="text-[#00BBFF]">{{ $highlight }}</span>
    </h2>

    <p class="text-sm text-gray-600 leading-relaxed max-w-sm mb-10">
        Your trusted destination for premium PC parts and components.
        Build, upgrade, and power your dream setup with quality hardware at competitive prices.
    </p>

    <button type="button" class="bg-[#00BBFF] hover:bg-[#00a6e0] transition-colors text-white text-sm font-semibold px-6 py-3 rounded-lg">
        {{ $buttonText }}
    </button>
</div>
