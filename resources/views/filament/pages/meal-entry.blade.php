<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form Section -->
        <form wire:submit.prevent="submit" class="no-print">
            {{ $this->form }}
        </form>

        <!-- Save Button Section -->
        @if(!empty($students))
            <div class="flex justify-end mb-4">
                <x-filament::button wire:click="saveMeals" color="success" size="lg">
                    Save Meal Data
                </x-filament::button>
            </div>
        @endif

        <!-- Students Table Section -->
        @if(!empty($students))
            <!-- Header Section -->
            <x-filament::section>
                <x-slot name="heading">
                    Meal Entry for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }} - {{ \App\Models\SchoolClass::find($selectedClass)?->name ?? 'Unknown Class' }}
                </x-slot>
                <x-slot name="description">
                    {{ count($students) }} active students found. Check the meals each student will have.
                </x-slot>

                <!-- Info Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Current Meal Rates
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Breakfast: ৳{{ \App\Models\MealRate::getCurrentRate('breakfast') }}</p>
                                <p>Lunch: ৳{{ \App\Models\MealRate::getCurrentRate('lunch') }}</p>
                                <p>Dinner: ৳{{ \App\Models\MealRate::getCurrentRate('dinner') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
