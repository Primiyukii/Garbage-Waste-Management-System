<x-admin-layout title="Add Customer">
    <div class="max-w-2xl bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.customers.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Temporary Password</label>
                <input type="password" name="password" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                    <select name="barangay_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select barangay</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->id }}" @selected(old('barangay_id') == $barangay->id)>
                                {{ $barangay->name }}, {{ $barangay->city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('barangay_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service (optional)</label>
                    <select name="service_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">None yet</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                    <input type="text" name="street" value="{{ old('street') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('street') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">House/Building No.</label>
                    <input type="text" name="house_number" value="{{ old('house_number') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('house_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Create Customer</button>
            </div>
        </form>
    </div>
</x-admin-layout>
