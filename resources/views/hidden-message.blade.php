<x-app-layout>
    <div class="container mx-auto p-4 py-5 flex-grow flex flex-col h-full overflow-hidden">
        <div class="flex flex-col w-full gap-6 px-4 py-6 h-screen items-center">
            <!-- Section untuk Upload Gambar -->
            <div class="card bg-gradient-to-r from-base-300 to-base-100 shadow-lg w-full lg:w-2/3 p-6 rounded-lg">
                <h2 class="text-secondary text-3xl font-bold text-center mb-4">Extract Hidden Message</h2>
                <p class="text-center text-base-content opacity-80 mb-6">
                    Upload an image containing a hidden message to extract it with ease. Supported formats: JPEG, PNG, GIF.
                </p>

                <!-- Form untuk Upload Gambar -->
                <form method="POST" action="{{ route('hidden-message') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-medium">Upload Your Image</span>
                        </label>
                        <input type="file" name="image" accept="image/*" class="file-input file-input-bordered w-full" required>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-block text-lg font-medium">Extract Message</button>
                </form>
            </div>

            <!-- Section untuk Menampilkan Pesan Tersembunyi -->
            @if (session('hiddenMessage'))
                <div class="card bg-secondary shadow-lg w-full lg:w-2/3 p-6 rounded-lg text-center">
                    <h2 class="text-white text-2xl font-bold mb-4">Hidden Message Extracted</h2>
                    <p class="text-white text-lg font-mono bg-opacity-80 p-4 rounded-lg bg-black shadow-md">
                        "{{ session('hiddenMessage') }}"
                    </p>
                </div>
            @endif

            <!-- Error Message -->
            @if ($errors->any())
                <div class="alert alert-error shadow-lg w-full lg:w-2/3">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.225 4.56l-.707-.707a1 1 0 00-1.414 0l-.707.707a1 1 0 000 1.414l.707.707a1 1 0 001.414 0l.707-.707a1 1 0 000-1.414zm11.193 0l-.707-.707a1 1 0 00-1.414 0l-.707.707a1 1 0 000 1.414l.707.707a1 1 0 001.414 0l.707-.707a1 1 0 000-1.414z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.342 9.342A8.001 8.001 0 1112 21.95m0-11.516a3 3 0 110-6 3 3 0 010 6zm0 0v3m0 3v1.5m-2.344-9h4.688m-4.688-4.999h4.688" />
                        </svg>
                        <span>Error: {{ $errors->first() }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
