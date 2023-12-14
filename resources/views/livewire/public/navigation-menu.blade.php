<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('favicon-32x32.png') }}"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        🏠️
                    </x-jet-nav-link>
                </div>
            </div>
            <!-- Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Teams Dropdown -->

                <!-- Workouts Dropdown -->

                <!-- Goal Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                            🏆
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                            </svg>
                            </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Measurements -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Goals') }}
                            </div>

                            <x-jet-nav-link class="pl-4" href="{{ route('createGoals') }}"
                                            :active="request()->routeIs('createGoals')">
                                🏆 Create Goals
                            </x-jet-nav-link>
                            <div class="border-t border-gray-100"></div>

                            <x-jet-nav-link class="pl-4" href="{{ route('viewGoals') }}"
                                            :active="request()->routeIs('viewGoals')">
                                📈 View Goals
                            </x-jet-nav-link>

                        </x-slot>
                    </x-jet-dropdown>
                </div>

                <!-- Plan Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                            ✍️
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                            </svg>
                            </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Measurements -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Plan') }}
                            </div>

                            <x-jet-nav-link class="pl-4" href="{{ route('createPlan') }}"
                                            :active="request()->routeIs('createPlan')">
                                ✍️ Create Plan
                            </x-jet-nav-link>
                            <div class="border-t border-gray-100"></div>


                            <x-jet-nav-link class="pl-4" href="{{ route('planHistory') }}"
                                            :active="request()->routeIs('planHistory')">
                                📔 Switch Plan
                            </x-jet-nav-link>

                            <x-jet-nav-link class="pl-4" href="{{ route('plan') }}"
                                            :active="request()->routeIs('plan')">
                                ⚙️ Import Plan
                            </x-jet-nav-link>

                            <x-jet-nav-link class="pl-4" href="{{ route('exercises') }}"
                                            :active="request()->routeIs('exercises')">
                                ⚙️ Exercise Settings
                            </x-jet-nav-link>
                        </x-slot>
                    </x-jet-dropdown>
                </div>

                <!-- Workouts Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                            🏋️
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                            </svg>
                            </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Measurements -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Workouts') }}
                            </div>
                            <x-jet-nav-link class="pl-4" href="{{ route('viewExercises') }}"
                                            :active="request()->routeIs('viewExercises')">
                                🏋️ Exercise History
                            </x-jet-nav-link>
                            <div class="border-t border-gray-100"></div>

                            <x-jet-nav-link class="pl-4" href="{{ route('log') }}" :active="request()->routeIs('log')">
                                📔 Exercise Log
                            </x-jet-nav-link>
                        </x-slot>
                    </x-jet-dropdown>
                </div>

                <!-- Measurements Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                            📏
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                            </svg>
                            </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Measurements -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Measurements') }}
                            </div>

                            <x-jet-nav-link class="pl-4" href="{{ route('history') }}"
                                            :active="request()->routeIs('history')">
                                📉 Measurement History
                            </x-jet-nav-link>

                            <div class="border-t border-gray-100"></div>

                            <x-jet-nav-link class="pl-4" href="{{ route('track') }}"
                                            :active="request()->routeIs('track')">
                                📏 Create Measurement
                            </x-jet-nav-link>

                            <x-jet-nav-link class="pl-4" href="{{ route('skinFoldTest') }}"
                                            :active="request()->routeIs('skinFoldTest')">
                                📐 Create Skinfold
                            </x-jet-nav-link>
                        </x-slot>
                    </x-jet-dropdown>
                </div>

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                Guest

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                                </svg>
                                </button>
                                </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>
                            <x-jet-nav-link class="pl-4 block" href="{{ route('profile.show') }}"
                                            :active="request()->routeIs('exercises')">
                                ⚙️ {{ __('Profile') }}
                            </x-jet-nav-link>
                            <br/>

                            <div class="border-t border-gray-100"></div>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    🖥 {{ __('Dashboard') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('viewExercises') }}"
                                           :active="request()->routeIs('viewExercises')">
                    📊 {{ __('Exercises') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('log') }}" :active="request()->routeIs('log')">
                    📔 {{ __('Workout Log') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('history') }}" :active="request()->routeIs('history')">
                    📉 {{ __('Measurements') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('track') }}" :active="request()->routeIs('track')">
                    📏 {{ __('Measure') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('skinFoldTest') }}"
                                           :active="request()->routeIs('skinFoldTest')">
                    📐 {{ __('Skin Fold') }}
                </x-jet-responsive-nav-link>


                <x-jet-responsive-nav-link href="{{ route('createPlan') }}" :active="request()->routeIs('createPlan')">
                    ✍️ {{ __('Create Plan') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('createGoals') }}"
                                           :active="request()->routeIs('createGoals')">
                    🏆 {{ __('Create Goal') }}
                </x-jet-responsive-nav-link>

                <x-jet-responsive-nav-link href="{{ route('viewGoals') }}" :active="request()->routeIs('viewGoals')">
                    📈️ {{ __('View Goals') }}
                </x-jet-responsive-nav-link>

            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">

                    <div>
                        <div class="font-medium text-base text-gray-800">Guest</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-jet-responsive-nav-link href="{{ route('exercises') }}"
                                               :active="request()->routeIs('exercises')">
                        ⚙️ {{ __('Exercises') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('plan') }}" :active="request()->routeIs('plan')">
                        ⚙️ {{ __('Clone Plan') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('planHistory') }}"
                                               :active="request()->routeIs('planHistory')">
                        ⚙️ {{ __('Choose Plan') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('profile.show') }}"
                                               :active="request()->routeIs('profile.show')">
                        ⚙️ {{ __('Profile') }}
                    </x-jet-responsive-nav-link>

                    <x-jet-responsive-nav-link href="{{ route('login') }}"
                                               :active="request()->routeIs('exercises')">
                        🔓 {{ __('Login') }}
                    </x-jet-responsive-nav-link>
                </div>
            </div>
        </div>
</nav>
