<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Document::class;

    public function definition()
    {
        return [
            'employee_id' => User::where('role', 'employee')->inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'document_name' => $this->faker->word . '.pdf',
            'file_path' => 'documents/' . $this->faker->uuid . '.pdf',
            'expiry_date' => $this->faker->optional()->date(),
        ];
    }
}
