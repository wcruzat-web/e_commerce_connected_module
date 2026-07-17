<div class="bg-white rounded-xl shadow mt-6 p-6 animate-fade-up">

<div class="flex items-center gap-2 mb-1">
<img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/bell.svg"
    class="w-5 h-5" alt="">
<h2 class="font-semibold text-gray-800" data-i18n="set.notifications">
Notifications
</h2>
</div>

<p class="text-sm text-gray-500 mb-5" data-i18n="set.notifDesc">
Control what updates you'll receive in actions
</p>

<div class="divide-y">

@foreach($toggles as $toggle)

<div class="flex items-center justify-between py-4 {{ $loop->first ? 'pt-0' : '' }}">

<div>
<p class="font-medium text-sm" data-i18n="toggle.{{ $toggle['key'] }}">
{{ $toggle['title'] }}
</p>
<p class="text-xs text-gray-500 mt-1" data-i18n="toggle.{{ $toggle['key'] }}_desc">
{{ $toggle['description'] }}
</p>
</div>

<label class="relative inline-flex items-center cursor-pointer">

<input
type="checkbox"
name="{{ $toggle['key'] }}"
value="1"
{{ ($settings[$toggle['key']] ?? '0') === '1' ? 'checked' : '' }}
class="sr-only peer js-autosave">

<div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-sky-500 transition-colors"></div>

<div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>

</label>

</div>

@endforeach

</div>
<div class="flex justify-end mt-6">

<button class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
<span data-i18n="set.save">Save Changes</span>
</button>

</div>

</div>
