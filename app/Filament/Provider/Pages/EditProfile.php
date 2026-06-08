<?php

namespace App\Filament\Provider\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    //protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'My Account';
    protected static ?string $navigationLabel = 'My Profile';
    protected static ?string $title = 'My Profile';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.provider.pages.edit-profile';

    public ?array $profileData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $provider = auth('provider')->user();
        $this->profileForm->fill([
            'name'           => $provider->name,
            'email'          => $provider->email,
            'company_name'   => $provider->company_name,
            'contact_person' => $provider->contact_person,
            'phone'          => $provider->phone,
            'website'        => $provider->website,
            'tax_id'         => $provider->tax_id,
            'description'    => $provider->description,
            'address'        => $provider->address,
            'city'           => $provider->city,
            'state'          => $provider->state,
            'country'        => $provider->country,
            'postal_code'    => $provider->postal_code,
        ]);
    }

    protected function getForms(): array
    {
        return ['profileForm', 'passwordForm'];
    }

    public function profileForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Account Information')->schema([
                    Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                ])->columns(2),
                Forms\Components\Section::make('Company Information')->schema([
                    Forms\Components\TextInput::make('company_name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('contact_person')->maxLength(255),
                    Forms\Components\TextInput::make('phone')->tel()->maxLength(20),
                    Forms\Components\TextInput::make('website')->url()->maxLength(255),
                    Forms\Components\TextInput::make('tax_id')->label('Tax ID / RFC')->maxLength(50),
                    Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Address')->schema([
                    Forms\Components\TextInput::make('address')->maxLength(255)->columnSpanFull(),
                    Forms\Components\TextInput::make('city')->maxLength(100),
                    Forms\Components\TextInput::make('state')->maxLength(100),
                    Forms\Components\TextInput::make('country')->maxLength(100)->default('México'),
                    Forms\Components\TextInput::make('postal_code')->maxLength(20),
                ])->columns(2),
            ])
            ->statePath('profileData');
    }

    public function passwordForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Change Password')->schema([
                    Forms\Components\TextInput::make('current_password')
                        ->password()->required()->currentPassword('provider'),
                    Forms\Components\TextInput::make('password')
                        ->password()->required()->minLength(8)->confirmed(),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->password()->required()->label('Confirm Password'),
                ])->columns(2),
            ])
            ->statePath('passwordData');
    }

    public function saveProfile(): void
    {
        $data = $this->profileForm->getState();
        auth('provider')->user()->update($data);
        Notification::make()->title('Profile updated successfully.')->success()->send();
    }

    public function changePassword(): void
    {
        $data = $this->passwordForm->getState();
        auth('provider')->user()->update(['password' => Hash::make($data['password'])]);
        $this->passwordForm->fill();
        Notification::make()->title('Password changed successfully.')->success()->send();
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
