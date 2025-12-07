<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Month Selection Form -->
        <div>
            {{ $this->form }}
        </div>

        <!-- Monthly Billing Table -->
        <div>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
