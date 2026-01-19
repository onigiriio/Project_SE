<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->user_type === 'librarian';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'isbn' => ['required', 'string', 'unique:books,isbn'],
            'pages' => ['required', 'integer', 'min:1'],
            'publisher' => ['required', 'string', 'max:255'],
            'published_date' => ['required', 'date'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['integer', 'exists:genres,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Book title is required.',
            'author.required' => 'Author name is required.',
            'description.required' => 'Book description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'isbn.required' => 'ISBN is required.',
            'isbn.unique' => 'This ISBN is already registered.',
            'pages.required' => 'Number of pages is required.',
            'pages.integer' => 'Pages must be a whole number.',
            'pages.min' => 'Pages must be at least 1.',
            'publisher.required' => 'Publisher is required.',
            'published_date.required' => 'Published date is required.',
            'published_date.date' => 'Published date must be a valid date.',
            'cover_image.image' => 'Cover image must be an image file.',
            'cover_image.mimes' => 'Cover image must be a JPEG, PNG, JPG, or GIF file.',
            'cover_image.max' => 'Cover image must not exceed 2MB.',
            'genres.array' => 'Genres must be an array.',
            'genres.*.integer' => 'Each genre must be a valid ID.',
            'genres.*.exists' => 'One or more selected genres do not exist.',
        ];
    }
}
