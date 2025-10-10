<x-filament-panels::page>
    <form wire:submit="saveResults">
        {{ $this->form }}

        @if(!empty($students))
            <div class="mt-6 flex justify-end">
                <x-filament::button 
                    type="submit"
                    color="success"
                    size="lg"
                    icon="heroicon-o-check-circle"
                >
                    Save All Results
                </x-filament::button>
            </div>
        @endif
    </form>
</x-filament-panels::page>
