<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected string $recordPassword;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $password = Str::random(12);
        $this->recordPassword = $password;

        $data['password'] = Hash::make($password);

        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        if ($user) {
            Mail::to($user->email)->send(new UserCredentialsMail($user, $this->recordPassword));
        }
    }
}
