<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create genres
        $fiction = Genre::create([
            'name' => 'Fiction',
            'slug' => 'fiction',
            'description' => 'Imaginative stories and narratives',
        ]);

        $mystery = Genre::create([
            'name' => 'Mystery',
            'slug' => 'mystery',
            'description' => 'Thrilling detective and crime stories',
        ]);

        $romance = Genre::create([
            'name' => 'Romance',
            'slug' => 'romance',
            'description' => 'Love stories and relationships',
        ]);

        $science = Genre::create([
            'name' => 'Science Fiction',
            'slug' => 'science-fiction',
            'description' => 'Futuristic and technological stories',
        ]);

        $fantasy = Genre::create([
            'name' => 'Fantasy',
            'slug' => 'fantasy',
            'description' => 'Magical worlds and adventures',
        ]);

        $biography = Genre::create([
            'name' => 'Biography',
            'slug' => 'biography',
            'description' => 'True stories about real people',
        ]);

        $selfHelp = Genre::create([
            'name' => 'Self-Help',
            'slug' => 'self-help',
            'description' => 'Personal development and improvement',
        ]);

        $history = Genre::create([
            'name' => 'History',
            'slug' => 'history',
            'description' => 'Historical events and periods',
        ]);

        // Sample books
        $booksData = [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'description' => 'A classic American novel set in the Jazz Age, exploring themes of wealth, love, and the American Dream.',
                'isbn' => '978-0743273565',
                'pages' => 180,
                'publisher' => 'Scribner',
                'published_date' => '1925-04-10',
                'price' => 12.99,
                'view_count' => 350,
                'rating' => 4.5,
                'rating_count' => 2500,
                'genres' => [$fiction],
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'description' => 'A dystopian masterpiece depicting a totalitarian society and the human struggle for freedom.',
                'isbn' => '978-0451524935',
                'pages' => 328,
                'publisher' => 'Signet',
                'published_date' => '1949-06-08',
                'price' => 13.99,
                'view_count' => 420,
                'rating' => 4.7,
                'rating_count' => 3100,
                'genres' => [$fiction, $science],
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'description' => 'An epic fantasy adventure featuring a reluctant hero and a quest for treasure.',
                'isbn' => '978-0547928227',
                'pages' => 366,
                'publisher' => 'Houghton Mifflin',
                'published_date' => '1937-09-21',
                'price' => 14.99,
                'view_count' => 480,
                'rating' => 4.8,
                'rating_count' => 2800,
                'genres' => [$fantasy],
            ],
            [
                'title' => 'Murder on the Orient Express',
                'author' => 'Agatha Christie',
                'description' => 'A brilliant detective mystery where Hercule Poirot must solve a locked-room murder on a train.',
                'isbn' => '978-0062693556',
                'pages' => 256,
                'publisher' => 'William Morrow',
                'published_date' => '1934-01-01',
                'price' => 11.99,
                'view_count' => 390,
                'rating' => 4.6,
                'rating_count' => 2300,
                'genres' => [$mystery],
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'description' => 'A timeless romance exploring social class and true love in Regency England.',
                'isbn' => '978-0141439518',
                'pages' => 432,
                'publisher' => 'Penguin Classics',
                'published_date' => '1813-01-28',
                'price' => 12.99,
                'view_count' => 360,
                'rating' => 4.7,
                'rating_count' => 3500,
                'genres' => [$romance, $fiction],
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'description' => 'An epic science fiction saga about politics, religion, and ecology on a desert planet.',
                'isbn' => '978-0441013593',
                'pages' => 688,
                'publisher' => 'Ace',
                'published_date' => '1965-06-01',
                'price' => 15.99,
                'view_count' => 410,
                'rating' => 4.6,
                'rating_count' => 2700,
                'genres' => [$science, $fantasy],
            ],
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'description' => 'An authorized biography of the Apple founder, exploring his life, vision, and impact on technology.',
                'isbn' => '978-1451648546',
                'pages' => 656,
                'publisher' => 'Simon & Schuster',
                'published_date' => '2011-10-24',
                'price' => 17.99,
                'view_count' => 280,
                'rating' => 4.5,
                'rating_count' => 1900,
                'genres' => [$biography],
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'description' => 'Transform your life by understanding how small habits create remarkable results.',
                'isbn' => '978-0735211292',
                'pages' => 320,
                'publisher' => 'Avery',
                'published_date' => '2018-10-16',
                'price' => 16.99,
                'view_count' => 520,
                'rating' => 4.8,
                'rating_count' => 4200,
                'genres' => [$selfHelp],
            ],
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'description' => 'From the Big Bang to Black Holes: an exploration of the universe and its mysteries.',
                'isbn' => '978-0553380163',
                'pages' => 256,
                'publisher' => 'Bantam',
                'published_date' => '1988-04-01',
                'price' => 14.99,
                'view_count' => 340,
                'rating' => 4.4,
                'rating_count' => 2100,
                'genres' => [$science, $history],
            ],
            [
                'title' => 'The Midnight Library',
                'author' => 'Matt Haig',
                'description' => 'A magical library where every book represents a different life she could have lived.',
                'isbn' => '978-0525559474',
                'pages' => 288,
                'publisher' => 'Viking',
                'published_date' => '2020-08-13',
                'price' => 13.99,
                'view_count' => 450,
                'rating' => 4.6,
                'rating_count' => 2600,
                'genres' => [$fantasy, $fiction],
            ],
            [
                'title' => 'The Da Vinci Code',
                'author' => 'Dan Brown',
                'description' => 'A thrilling mystery involving art, history, and ancient secrets.',
                'isbn' => '978-0307474278',
                'pages' => 487,
                'publisher' => 'Anchor',
                'published_date' => '2003-03-18',
                'price' => 14.99,
                'view_count' => 500,
                'rating' => 4.5,
                'rating_count' => 3000,
                'genres' => [$mystery, $fiction],
            ],
            [
                'title' => 'The Alchemist',
                'author' => 'Paulo Coelho',
                'description' => 'A philosophical journey about following your dreams and discovering your personal legend.',
                'isbn' => '978-0061233845',
                'pages' => 224,
                'publisher' => 'HarperOne',
                'published_date' => '1988-06-01',
                'price' => 12.99,
                'view_count' => 470,
                'rating' => 4.5,
                'rating_count' => 3300,
                'genres' => [$fiction, $selfHelp],
            ],
        ];

        // Create books and attach genres
        foreach ($booksData as $data) {
            $genres = $data['genres'];
            unset($data['genres']);

            $book = Book::create($data);
            $book->genres()->attach($genres);
        }

        // Create sample reviews
        $user = User::first();
        if ($user) {
            $books = Book::all();
            foreach ($books->take(5) as $book) {
                Review::create([
                    'book_id' => $book->id,
                    'user_id' => $user->id,
                    'rating' => rand(3, 5),
                    'comment' => 'This is a great book! Really enjoyed reading it and would recommend it to others.',
                ]);
            }
        }
    }
}
