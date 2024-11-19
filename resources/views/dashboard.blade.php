<x-app-layout>
    <div class="container mx-auto p-4 py-5 flex-grow flex flex-col h-full overflow-hidden">
        <!-- Menambahkan overflow-hidden pada container utama -->
        <div class="flex flex-col lg:flex-row w-full gap-4 px-4 py-2 h-screen">
            <!-- Sidebar Section (User List) -->
            <div id="user-list"
                class="scrollbar-thin scrollbar-thumb-rounded scrollbar-thumb-base-100 scrollbar-track-gray-700 hover:scrollbar-thin card bg-base-300 rounded-box lg:w-1/4 h-[80%] overflow-y-auto transition-all duration-300 ease-in-out ">
                <!-- Wrapper untuk search bar dengan z-index tinggi -->
                <div class="sticky top-0 bg-base-300 z-50 p-3">
                    <label class="input flex items-center gap-2">
                        <input type="text" class="grow border-0 focus:outline-none focus:ring-0" placeholder="Search" />
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-4 w-4 opacity-70 text-secondary">
                            <path fill-rule="evenodd"
                                d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                                clip-rule="evenodd" />
                        </svg>
                    </label>
                </div>
                <!-- Daftar User -->
                <ul class="menu bg-base-300 w-full space-y-2 relative p-3">
                    @foreach ($users as $user)
                        <li>
                            <a class="{{ request()->is('chat/' . $user->username) ? 'text-secondary' : '' }}"
                                href="{{ route('chat', ['user' => $user->id]) }}"
                                onclick="openChat('{{ $user->id }}'); event.preventDefault();">
                                {{ $user->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- Chat Section -->
            <div id="chat-section"
                class="scard bg-base-300 rounded-box lg:w-3/4 h-[80%] flex flex-col transition-all duration-300 ease-in-out">
                <!-- Chat Container -->
                <p>hello</p>
            </div>
        </div>
    </div>
</x-app-layout>
