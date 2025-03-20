<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\SavedSearch;
use Illuminate\Support\Facades\Auth;


class ProfileSearch extends Component
{
    use WithPagination;

    // Search parameters
    public $minAge = 18;
    public $maxAge = 99;
    public $location = '';
    public $distance = 50; // in miles/km
    public $sexualPreference = [];
    public $relationshipType = [];
    public $minHeight = 140; // in cm
    public $maxHeight = 220; // in cm
    public $bodyType = [];
    public $wantsChildren = null;
    public $smoking = null;
    public $ethnicity = [];
    public $spiritualBeliefs = [];

    // Saved search functionality
    public $savedSearches = [];
    public $currentSearchId = null;
    public $searchName = '';
    public $isEditingSearch = false;

    // Modal functionality
    public $selectedUserId = null;

    protected $listeners = [
        'refreshSearch' => '$refresh',
        'open-profile-modal' => 'openProfileModal'
    ];

    public function mount()
    {
        if (Auth::check()) {
            $this->loadSavedSearches();
        }
    }

    public function loadSavedSearches()
    {
        $this->savedSearches = Auth::user()->savedSearches()->get();
    }

    public function loadSearch($searchId)
    {
        $search = SavedSearch::findOrFail($searchId);

        // Ensure the search belongs to the current user
        if ($search->user_id !== Auth::id()) {
            return;
        }

        $this->currentSearchId = $search->id;
        $this->searchName = $search->name;

        $criteria = json_decode($search->criteria, true);

        // Load all search parameters
        $this->minAge = $criteria['minAge'] ?? 18;
        $this->maxAge = $criteria['maxAge'] ?? 99;
        $this->location = $criteria['location'] ?? '';
        $this->distance = $criteria['distance'] ?? 50;
        $this->sexualPreference = $criteria['sexualPreference'] ?? [];
        $this->relationshipType = $criteria['relationshipType'] ?? [];
        $this->minHeight = $criteria['minHeight'] ?? 140;
        $this->maxHeight = $criteria['maxHeight'] ?? 220;
        $this->bodyType = $criteria['bodyType'] ?? [];
        $this->wantsChildren = $criteria['wantsChildren'] ?? null;
        $this->smoking = $criteria['smoking'] ?? null;
        $this->ethnicity = $criteria['ethnicity'] ?? [];
        $this->spiritualBeliefs = $criteria['spiritualBeliefs'] ?? [];

        $this->isEditingSearch = true;
    }

    public function saveSearch()
    {
        if (empty($this->searchName)) {
            $this->addError('searchName', 'Please provide a name for your search.');
            return;
        }

        $criteria = [
            'minAge' => $this->minAge,
            'maxAge' => $this->maxAge,
            'location' => $this->location,
            'distance' => $this->distance,
            'sexualPreference' => $this->sexualPreference,
            'relationshipType' => $this->relationshipType,
            'minHeight' => $this->minHeight,
            'maxHeight' => $this->maxHeight,
            'bodyType' => $this->bodyType,
            'wantsChildren' => $this->wantsChildren,
            'smoking' => $this->smoking,
            'ethnicity' => $this->ethnicity,
            'spiritualBeliefs' => $this->spiritualBeliefs,
        ];

        if ($this->isEditingSearch && $this->currentSearchId) {
            // Update existing search
            $search = SavedSearch::findOrFail($this->currentSearchId);

            if ($search->user_id !== Auth::id()) {
                return;
            }

            $search->update([
                'name' => $this->searchName,
                'criteria' => json_encode($criteria),
            ]);

            $this->dispatch('notify', ['message' => 'Search updated successfully']);
        } else {
            // Create new search
            Auth::user()->savedSearches()->create([
                'name' => $this->searchName,
                'criteria' => json_encode($criteria),
            ]);

            $this->dispatch('notify', ['message' => 'Search saved successfully']);
        }

        $this->loadSavedSearches();
        $this->reset('searchName', 'isEditingSearch', 'currentSearchId');
    }

    public function deleteSearch($searchId)
    {
        $search = SavedSearch::findOrFail($searchId);

        if ($search->user_id !== Auth::id()) {
            return;
        }

        $search->delete();
        $this->loadSavedSearches();

        if ($this->currentSearchId === $searchId) {
            $this->reset('searchName', 'isEditingSearch', 'currentSearchId');
        }

        $this->dispatch('notify', ['message' => 'Search deleted successfully']);
    }

    public function resetFilters()
    {
        $this->reset([
            'minAge', 'maxAge', 'location', 'distance', 'sexualPreference',
            'relationshipType', 'minHeight', 'maxHeight', 'bodyType',
            'wantsChildren', 'smoking', 'ethnicity', 'spiritualBeliefs',
            'searchName', 'isEditingSearch', 'currentSearchId'
        ]);

        $this->resetPage();
    }

    public function updatedMinAge()
    {

        if ($this->minAge > $this->maxAge) {
            $this->maxAge = $this->minAge;

        }
    }

    public function updatedMaxAge()
    {
        if ($this->maxAge < $this->minAge) {
            $this->minAge = $this->maxAge;
        }
    }

    public function updatedMinHeight()
    {
        if ($this->minHeight > $this->maxHeight) {
            $this->maxHeight = $this->minHeight;
        }
    }

    public function updatedMaxHeight()
    {
        if ($this->maxHeight < $this->minHeight) {
            $this->minHeight = $this->maxHeight;
        }
    }

    public function render()
    {
        $query = User::query()
            // ->whereNotNull('profile_completed_at') // Only users who have completed their profile
            ->where('id', '!=', Auth::id()); // Exclude current user

        // Apply age filter
        $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= ?', [$this->minAge])
              ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) <= ?', [$this->maxAge]);

        // Apply location filter if provided
        if (!empty($this->location) && $this->distance > 0) {
            // This implementation will depend on how you store location data
            // For example, if using latitude/longitude:
            // $query->whereRaw('ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) <= ?', [$lon, $lat, $this->distance * 1609.34]);
            //
            // This is a simplified placeholder. You'll need to implement location-based filtering
            // based on your database structure and geospatial capabilities
        }

        // Apply sexual preference filter
        if (!empty($this->sexualPreference)) {
            $query->whereIn('sexual_preference', $this->sexualPreference);
        }

        // Apply relationship type filter
        if (!empty($this->relationshipType)) {
            // dd($this->relationshipType);
            $query->whereIn('relationship_type', $this->relationshipType);
            // dd($query);
        }

        // Apply height filter
        $query->where('height', '>=', $this->minHeight)
              ->where('height', '<=', $this->maxHeight);

        // Apply body type filter
        if (!empty($this->bodyType)) {
            $query->whereIn('body_type', $this->bodyType);
        }

        // Apply wants children filter
        if ($this->wantsChildren !== null) {
            $query->where('wants_children', $this->wantsChildren);
        }

        // Apply smoking filter
        if ($this->smoking !== null) {
            $query->where('smoking', $this->smoking);
        }

        // Apply ethnicity filter
        if (!empty($this->ethnicity)) {
            $query->whereIn('ethnicity', $this->ethnicity);
        }

        // Apply spiritual beliefs filter
        if (!empty($this->spiritualBeliefs)) {
            $query->whereIn('spiritual_belief', $this->spiritualBeliefs);
        }

        $users = $query->paginate(12);

        // Get dropdown options from configuration or database
        $bodyTypes = \App\Enums\body_type::cases();
        $relationshipTypes = \App\Enums\relationship_types::cases();
        // dd($relationshipTypes);
        $bodyTypes = \App\Enums\body_type::cases();
        $ethnicities = \App\Enums\ethnicities::cases();
        $spiritualBeliefs = \App\Enums\spiritual_beliefs::cases();
        $sexualPreferences = \App\Enums\sexual_preferences::cases();


        return view('livewire.profile-search', [
            'users' => $users,
            'relationshipTypes' => $relationshipTypes,
            'bodyTypes' => $bodyTypes,
            'ethnicities' => $ethnicities,
            'spiritualBeliefs' => $spiritualBeliefs,
            'sexualPreferences' => $sexualPreferences,
        ]);
    }
}
