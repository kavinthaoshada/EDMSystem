<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentExpiryReminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentExpiryReminder>
 */
class DocumentExpiryReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DocumentExpiryReminder::class;

    public function definition()
    {
        return [
            'document_id' => Document::inRandomOrder()->first()->id,
            'employee_id' => User::where('role', 'employee')->inRandomOrder()->first()->id,
            'reminder_date' => $this->faker->date(),
            'notified' => $this->faker->boolean(),
        ];
    }
}
