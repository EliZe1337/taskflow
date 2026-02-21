<?php

namespace App\Http\Controllers;

use App\Models\notes;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Note;


class NoteController extends Controller
{
    public function index(Request $request) : View
    {
        $user = $request->user();
        $notes = Note::forUser($user)->get(['title', 'body','id']);

        return view('notes.index', compact('notes'));



    }
    public function show(Note $note){
        if ($note->user_id != auth()->user()->id) {
            abort(403);
        }
        return view('notes.show', compact('note'));
    }

    public function create(){


        return view('notes.create');


    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $request->user()->notes()->create($validated);

        return redirect('/notes')->with('success', 'Заметка создана!');
    }
    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $note->update($validated);

        return redirect('/notes/' . $note->id)->with('success', 'Сохранено!');
    }
}
