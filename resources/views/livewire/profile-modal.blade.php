<div>
    @if($user)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Photo & Basic Info -->
            <div class="md:col-span-1">
                <div class="mb-4">
                    @if($user->profile_photo_path)
                        <img
                            src="{{ asset('storage/' . $user->profile_photo_path) }}"
                            alt="{{ $user->name }}"
                            class="rounded-lg w-full object-cover">
                    @else
                        <div class="bg-gray-200 flex items-center justify-center w-full h-64 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                        <span class="text-sm font-medium bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                            {{ \Carbon\Carbon::parse($user->date_of_birth)->age }}
                        </span>
                    </div>

                    @if($user->city && $user->state)
                        <p class="text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $user->city }}, {{ $user->state }}
                        </p>
                    @endif

                    @if(isset($user->last_active_at))
                        <p class="text-sm text-gray-500">
                            Last active: {{ $user->last_active_at->diffForHumans() }}
                        </p>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @if(isset($user->relationship_type))
                        <div class="text-sm">
                            <span class="text-gray-500">Looking for:</span>
                            <p class="font-medium">{{ config('app.relationship_types')[$user->relationship_type] ?? 'Not specified' }}</p>
                        </div>
                    @endif

                    @if(isset($user->height))
                        <div class="text-sm">
                            <span class="text-gray-500">Height:</span>
                            <p class="font-medium">{{ number_format($user->height) }} cm</p>
                        </div>
                    @endif

                    @if(isset($user->body_type))
                        <div class="text-sm">
                            <span class="text-gray-500">Body type:</span>
                            <p class="font-medium">{{ config('app.body_types')[$user->body_type] ?? 'Not specified' }}</p>
                        </div>
                    @endif

                    @if(isset($user->ethnicity))
                        <div class="text-sm">
                            <span class="text-gray-500">Ethnicity:</span>
                            <p class="font-medium">{{ config('app.ethnicities')[$user->ethnicity] ?? 'Not specified' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Details -->
            <div class="md:col-span-2 space-y-6">
                <!-- About Me -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">About Me</h3>
                    <p class="text-gray-600">
                        {{ $user->bio ?? 'No bio available.' }}
                    </p>
                </div>

                <!-- Preferences -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Preferences</h3>
                    <div class="grid grid-cols-2 gap-y-4">
                        <div class="text-sm">
                            <span class="text-gray-500">Sexual Preference:</span>
                            <p class="font-medium">{{ config('app.sexual_preferences')[$user->sexual_preference] ?? 'Not specified' }}</p>
                        </div>

                        <div class="text-sm">
                            <span class="text-gray-500">Smoking:</span>
                            <p class="font-medium">
                                @if($user->smoking === 1)
                                    Smoker
                                @elseif($user->smoking === 0)
                                    Non-smoker
                                @elseif($user->smoking === 2)
                                    Occasionally
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>

                        <div class="text-sm">
                            <span class="text-gray-500">Wants Children:</span>
                            <p class="font-medium">
                                @if($user->wants_children === 1)
                                    Yes
                                @elseif($user->wants_children === 0)
                                    No
                                @elseif($user->wants_children === 2)
                                    Maybe
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>

                        <div class="text-sm">
                            <span class="text-gray-500">Spiritual Beliefs:</span>
                            <p class="font-medium">{{ config('app.spiritual_beliefs')[$user->spiritual_belief] ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Interests -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Interests</h3>
                    <div class="flex flex-wrap gap-2">
                        @if(isset($user->interests) && $user->interests->count() > 0)
                            @foreach($user->interests as $interest)
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">
                                    {{ $interest->name }}
                                </span>
                            @endforeach
                        @else
                            <p class="text-gray-500">No interests listed.</p>
                        @endif
                    </div>
                </div>

                <!-- Additional Photos -->
                @if(isset($user->photos) && $user->photos->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Photos</h3>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($user->photos as $photo)
                                <div class="aspect-w-1 aspect-h-1">
                                    <img
                                        src="{{ asset('storage/' . $photo->path) }}"
                                        alt="Photo of {{ $user->name }}"
                                        class="object-cover w-full h-full rounded">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-600">Unable to load profile information.</p>
        </div>
    @endif
</div>
