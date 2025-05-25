<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-10 shadow-sm sm:rounded-lg">

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="py-3 w-full rounded-3xl bg-red-500 text-white">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                <form method="POST" action="{{ route('admin.categories.update', $category) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input value="{{ $category->name }}" id="name" class="block mt-1 w-full"
                            type="text" name="name" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="icon" :value="__('icon')" />
                        <img id="iconPreview" src="{{ Storage::url($category->icon) }}" alt="{{ $category->name }}"
                            class="w-16 h-16 object-cover rounded-full">

                        @if ($category->icon)
                            <p class="text-sm text-gray-500 mt-2">Current icon will be replaced with the new one.</p>
                        @else
                            <p class="text-sm text-gray-500 mt-2">No icon uploaded yet. Please upload a new one.</p>
                        @endif

                        <x-text-input id="icon" class="block mt-1 w-full" type="file" name="icon"
                            onchange="previewIcon(event)" autofocus autocomplete="icon" />
                        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">

                        <button type="submit" class="font-bold py-4 px-6 bg-indigo-700 text-white rounded-full">
                            Update Category
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function previewIcon(event) {
    const input = event.target;
    const preview = document.getElementById('iconPreview');
    const file = input.files[0];

    if (file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, WebP)');
            input.value = ''; // reset file input
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

</script>
