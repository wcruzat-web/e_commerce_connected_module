<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
        class="mt-6 border rounded-2xl p-6 bg-white shadow-sm">
        @csrf

        <div class="flex justify-between items-center mb-5">

            <h2 class="font-semibold text-lg">
                Profile Information
            </h2>

            <button type="submit"
                class="border-2 border-sky-500 text-sky-500 hover:bg-sky-500 hover:text-white px-8 py-2 rounded-lg font-medium transition">
                Save Changes
            </button>

        </div>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">

            <div class="flex flex-col items-center shrink-0">

                <div class="relative">

                    <div class="w-36 h-36 rounded-full bg-sky-200 flex items-center justify-center text-4xl overflow-hidden">
                        @if($customer->profile_picture)
                            <img id="preview" src="{{ Str::startsWith($customer->profile_picture, ['http://', 'https://']) ? $customer->profile_picture : asset($customer->profile_picture) }}" alt="{{ $customer->full_name }}"
                                class="w-full h-full object-cover">
                        @else
                            <span id="preview" class="text-sky-600 font-bold">
                                {{ strtoupper(substr($customer->first_name, 0, 1)) }}{{ strtoupper(substr($customer->last_name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <button type="button" onclick="document.getElementById('profile_picture').click()"
                        class="absolute bottom-1 right-1 bg-white rounded-full shadow p-2 hover:bg-gray-100"
                        title="Upload new photo">
                        <img src="https://cdn.jsdelivr.net/npm/lucide-static@0.460.0/icons/camera.svg"
                            class="w-5 h-5" alt="Upload">
                    </button>

                    <input type="file" id="profile_picture" name="profile_picture"
                        accept="image/*" class="hidden">
                </div>

                <p class="text-xs text-gray-400 mt-2 text-center">JPG/PNG, max 2MB</p>

            </div>

            <div class="flex-1">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="font-medium">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="font-medium">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="font-medium">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="font-medium">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}"
                            class="mt-2 w-full border rounded px-3 py-2">
                    </div>

                </div>

            </div>

        </div>
    </form>
