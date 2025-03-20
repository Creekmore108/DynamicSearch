<div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Search Filters Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Find Your Match</h2>

                <!-- Saved Search Controls -->
                <div class="flex space-x-2">
                    @auth
                        <button
                            type="button"
                            x-data=""
                            @click="$dispatch('open-save-modal')"
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                            {{ $isEditingSearch ? 'Update Search' : 'Save Search' }}
                        </button>

                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition">
                            Reset
                        </button>

                        <button
                            type="button"
                            x-data=""
                            @click="$dispatch('open-help-modal')"
                            class="px-2 py-2 text-gray-500 hover:text-gray-700 transition"
                            title="Search Help">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    @endauth
                </div>

                <!-- Save Search Modal -->
                <div
                    x-data="{ showSaveModal: false }"
                    @open-save-modal.window="showSaveModal = true"
                    x-show="showSaveModal"
                    style="display: none"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                    <div
                        @click.outside="showSaveModal = false"
                        class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                        <h3 class="text-xl font-bold mb-4">
                            {{ $isEditingSearch ? 'Update Saved Search' : 'Save Your Search' }}
                        </h3>

                        <div class="mb-4">
                            <label for="searchName" class="block text-sm font-medium text-gray-700 mb-1">
                                Search Name
                            </label>
                            <input
                                type="text"
                                id="searchName"
                                wire:model="searchName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="e.g., My Ideal Match">
                            @error('searchName')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="showSaveModal = false"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition">
                                Cancel
                            </button>
                            <button
                                type="button"
                                wire:click="saveSearch"
                                @click="showSaveModal = false"
                                class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                                {{ $isEditingSearch ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saved Searches Section -->
            @auth
                @if(count($savedSearches) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Your Saved Searches</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($savedSearches as $search)
                                <div class="flex items-center space-x-1">
                                    <button
                                        type="button"
                                        wire:click="loadSearch({{ $search->id }})"
                                        class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded-full hover:bg-gray-200 transition">
                                        {{ $search->name }}
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="deleteSearch({{ $search->id }})"
                                        class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Search Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Age Range -->
                <div x-data="{ min: @entangle('minAge'), max: @entangle('maxAge') }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Age Range</label>
                    <div class="flex items-center space-x-2">
                        <input
                            type="number"
                            wire:model.live="minAge"
                            min="18"
                            max="99"
                            class="w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <span>to</span>
                        <input
                            type="number"
                            wire:model.live="maxAge"
                            min="18"
                            max="99"
                            class="w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <!-- Location & Distance -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <div class="flex space-x-2">
                        <input
                            type="text"
                            id="location"
                            wire:model.live.debounce.300ms="location"
                            placeholder="City, State or Zip"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <select
                            wire:model.live="distance"
                            class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="5">5 miles</option>
                            <option value="10">10 miles</option>
                            <option value="25">25 miles</option>
                            <option value="50">50 miles</option>
                            <option value="100">100 miles</option>
                            <option value="500">500+ miles</option>
                        </select>
                    </div>
                </div>

                <!-- Sexual Preference -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sexual Preference</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(App\Enums\sexual_preferences::cases() as $case)
                            <label class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    wire:model.live="sexualPreference"
                                    value="{{ $case->value }}"
                                    class="rounded text-purple-600 focus:ring-purple-500">
                                <span>{{ $case->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Relationship Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Relationship Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(App\Enums\relationship_types::cases() as $case)
                            <label class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    wire:model.live="relationshipType"
                                    value="{{ $case->value }}"
                                    class="rounded text-purple-600 focus:ring-purple-500">
                                <span>{{ $case->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Height Range -->
                <div x-data="{ min: @entangle('minHeight'), max: @entangle('maxHeight') }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Height Range (cm)</label>
                    <div class="flex items-center space-x-2">
                        <input
                            type="number"
                            wire:model.live="minHeight"
                            min="140"
                            max="220"
                            class="w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <span>to</span>
                        <input
                            type="number"
                            wire:model.live="maxHeight"
                            min="140"
                            max="220"
                            class="w-16 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <!-- Body Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(App\Enums\body_types::cases() as $case)
                            <label class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    wire:model.live="bodyType"
                                    value="{{ $case->value }}"
                                    class="rounded text-purple-600 focus:ring-purple-500">
                                <span>{{ $case->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Wants Children -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Wants Children</label>
                    <select
                        wire:model.live="wantsChildren"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Any</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                        <option value="2">Maybe</option>
                    </select>
                </div>

                <!-- Smoking -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Smoking</label>
                    <select
                        wire:model.live="smoking"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Any</option>
                        <option value="1">Smoker</option>
                        <option value="0">Non-smoker</option>
                        <option value="2">Occasionally</option>
                    </select>
                </div>

                <!-- Ethnicity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ethnicity</label>
                    <div class="overflow-y-auto max-h-32 pr-2 grid grid-cols-2 gap-2">
                        @foreach(App\Enums\ethnicities::cases() as $case)
                            <label class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    wire:model.live="ethnicity"
                                    value="{{ $case->value }}"
                                    class="rounded text-purple-600 focus:ring-purple-500">
                                <span>{{ $case->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Spiritual Beliefs -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spiritual Beliefs</label>
                    <div class="overflow-y-auto max-h-32 pr-2 grid grid-cols-2 gap-2">
                        @foreach($spiritualBeliefs as $value => $label)
                            <label class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    wire:model.live="spiritualBeliefs"
                                    value="{{ $value }}"
                                    class="rounded text-purple-600 focus:ring-purple-500">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results Section -->
        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Results</h3>

            @if($users->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <p class="text-yellow-700">No profiles match your search criteria. Try adjusting your filters.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($users as $user)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                            <a href="#">
                                <div class="relative aspect-w-1 aspect-h-1">
                                    @if($user->profile_photo_path)
                                        <img
                                            src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                            alt="{{ $user->name }}"
                                            class="object-cover w-full h-full">
                                    @else
                                        <div class="bg-gray-200 flex items-center justify-center w-full h-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <h4 class="font-semibold text-lg truncate">{{ $user->name }}</h4>
                                        <span class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($user->date_of_birth)->age }}
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        @if($user->city && $user->state)
                                            <p>{{ $user->city }}, {{ $user->state }}</p>
                                        @endif

                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @foreach(array_slice([
                                                $relationshipTypes[$user->relationship_type] ?? null,
                                                $bodyTypes[$user->body_type] ?? null,
                                                $user->height ? number_format($user->height) . 'cm' : null
                                            ], 0, 3) as $tag)
                                                @if($tag)
                                                    <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                                                        {{ $tag }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        @click="$dispatch('open-profile-modal', {userId: {{ $user->id }}})"
                                        class="mt-3 w-full px-3 py-1 text-center text-sm text-purple-600 border border-purple-200 rounded hover:bg-purple-50 transition">
                                        Quick View
                                    </button>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Notification Toast -->
    <div
        x-data="{ show: false, message: '' }"
        @notify.window="show = true; message = $event.detail.message; setTimeout(() => { show = false }, 3000)"
        x-show="show"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 right-4 z-50 max-w-sm bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg"
        style="display: none">
        <div class="flex items-center justify-between">
            <p x-text="message"></p>
            <button @click="show = false" class="ml-4 text-white hover:text-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
