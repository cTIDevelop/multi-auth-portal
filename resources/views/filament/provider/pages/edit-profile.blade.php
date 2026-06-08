<x-filament-panels::page>
    <form wire:submit="saveProfile">
        {{ $this->profileForm }}
        <div class="mt-6">
            <x-filament::button type="submit">
                Save Profile
            </x-filament::button>
        </div>
    </form>

    <div class="mt-10">
        <form wire:submit="changePassword">
            {{ $this->passwordForm }}
            <div class="mt-6">
                <x-filament::button type="submit" color="warning">
                    Change Password
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
