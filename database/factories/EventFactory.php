<?php

namespace Database\Factories;
use Illuminate\Support\Str;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{

    protected $model = Event::class;
    public function definition()
    {
        return [
            'id' => Str::orderedUuid(),
            'name' => $this->faker->text(35),
            'slug' => $this->faker->unique()->name(),
            'startAt' => now(),
            'endAt' => date('Y-m-d H:i:s', strtotime('+10 days'))
        ];
    }
}
