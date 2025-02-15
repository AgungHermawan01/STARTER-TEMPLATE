<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationEcception;
use App\Models\Book;

class AdminController extends Controller
{
    public function __construct()
    {
        $this-middleware('auth');

    }
    public function books()
    {
        $user = Auth::user();
        $books = Book::all();
        return view ('book', compact('user','books'));
    }
    public function submit_book(Request $req)
    {
        $validate = $req->validate([
            'judul' => 'required|max:255',
            'penulis' => 'required',
            'tahun'  => 'required',
            'penerbit' => 'required',

        ]);
            $book = new Book;
            $book->judul = $req->get('judul');
            $book->penulis = $req->get('penulis');
            $book->tahun = $req->get('tahun');
            $book->penerbit = $req->get('penerbit');

        if ($req->hasFile('cover')){
            $extension = $req->file('cover')->extension();
            $filename = 'cover_buku_'.time().'.'.$extension;
            $req->file('cover')->storeAs(
                'public/cover_buku', $filename
            );

            $book->cover =$filename;
        }
        $book->save();
        $notificaion = array(
            'message' => 'Data buku berhasil ditambahkan',
            'alert-type' => 'success'
        );
            return redirect()->route('admin.books')->with($notificaion);
    }
}
    