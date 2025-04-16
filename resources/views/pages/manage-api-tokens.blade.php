<x-filament-panels::page>

    <x-filament-panels::form id="form" wire:submit="create">
        {{ $this->form }}

        <div class="text-right">
            <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
        </div>
    </x-filament-panels::form>

    {{ $this->table }}

</x-filament-panels::page>
