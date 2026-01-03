<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use App\Models\User;

class CatalogueViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogue_renders_with_books()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $book = Book::factory()->create(['title' => 'Catalogue Book']);

        $response = $this->actingAs($user)->get(route('books.catalogue'));

        $response->assertStatus(200)
                 ->assertViewIs('books.catalogue')
                 ->assertSee('Catalogue Book');
    }
}
