<div class="bg-white rounded-xl shadow mt-8 p-6 animate-fade-up">

<div class="flex items-center gap-2 mb-1">
<img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/settings.svg"
    class="w-5 h-5" alt="">
<h2 class="font-semibold text-gray-800" data-i18n="set.preferences">
Preferences
</h2>
</div>

<p class="text-sm text-gray-500 mb-5" data-i18n="set.prefDesc">
A general view of saying properties
</p>

<div class="space-y-5">

<div>
<label class="block text-sm font-medium mb-2" data-i18n="set.language">
Language
</label>
<select id="language" name="language" class="w-full border rounded-lg px-4 py-2 text-sm">
<option value="English" {{ ($settings['language'] ?? '') === 'English' ? 'selected' : '' }}>English</option>
<option value="Filipino" {{ ($settings['language'] ?? '') === 'Filipino' ? 'selected' : '' }}>Filipino</option>
</select>
</div>

<div>
<label class="block text-sm font-medium mb-2" data-i18n="set.currency">
Currency
</label>
<select id="currency" name="currency" class="w-full border rounded-lg px-4 py-2 text-sm">
<option value="PHP" {{ ($settings['currency'] ?? '') === 'PHP' ? 'selected' : '' }}>PHP</option>
<option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD</option>
</select>
</div>

<div>
<label class="block text-sm font-medium mb-2" data-i18n="set.theme">
Theme
</label>
<select id="theme" name="theme" class="w-full border rounded-lg px-4 py-2 text-sm">
<option value="Light" {{ ($settings['theme'] ?? '') === 'Light' ? 'selected' : '' }}>Light</option>
<option value="Dark" {{ ($settings['theme'] ?? '') === 'Dark' ? 'selected' : '' }}>Dark</option>
</select>
</div>

</div>

</div>
