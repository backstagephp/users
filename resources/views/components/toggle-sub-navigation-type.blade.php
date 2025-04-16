<div wire:click="toggleSubNavigationType" class="cursor-pointer">
    <x-filament::icon :icon="$icon" class="w-6 h-auto fi-icon-btn-icon text-gray-400" 
    x-tooltip.raw="{{ __('Toggle subnavigation, current: :current', ['current' => $current])}}"/>
</div>
