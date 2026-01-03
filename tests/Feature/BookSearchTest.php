<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use App\Models\User;

class BookSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_finds_book_and_renders_catalogue_view()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $book = Book::factory()->create(['title' => 'Hello Unique Book']);

        $response = $this->actingAs($user)->get(route('books.index', ['q' => 'Hello']));

        $response->assertStatus(200)
                 ->assertSee('Hello Unique Book')
                 ->assertViewIs('books.catalogue');
    }
}
