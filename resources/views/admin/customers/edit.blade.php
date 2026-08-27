<x-admin-layout title="Edit Customer">
    <div class="max-w-2xl bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $customer->user->name) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->user->email) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->user->phone) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                    <select name="barangay_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->id }}" @selected(old('barangay_id', $customer->barangay_id) == $barangay->id)>
                                {{ $barangay->name }}, {{ $barangay->city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('barangay_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <select name="service_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">None</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id', $customer->service_id) == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                    <input type="text" name="street" value="{{ old('street', $customer->street) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('street') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">House/Building No.</label>
                    <input type="text" name="house_number" value="{{ old('house_number', $customer->house_number) }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('house_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.customers.show', $customer) }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-admin-layout>
