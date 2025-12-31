<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Survey; 

class SurveyFactory extends Factory
{
    protected $model = Survey::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => Str::slug($this->faker->sentence),
            'image' => 'surveyFactory.jpg',
            'theme' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'url_website' => 'https://spmb.polinema.ac.id/info/halaman/detail/program-studi',
            'embed_design' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Ffile%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Ftype%3Ddesign%26node-id%3D6%253A5%26mode%3Ddesign%26t%3DMS0FsghvdchwVDS1-1',
            'embed_prototype' => 'https://www.figma.com/embed?embed_host=share&url=https%3A%2F%2Fwww.figma.com%2Fproto%2FZBrJoFQclkOJHt8c13WjO0%2FMobile-E-Commerce---Porto-Naufal%3Fnode-id%3D6-5%26starting-point-node-id%3D6%253A5%26mode%3Ddesign%26t%3DqRgJWypwu4mOdUCX-1',
            'user_id' => 1,
        ];
    }
}

