<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function create()
    {
        return view('post-item');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:lost,found',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'location' => 'nullable|string',
            'date_occurred' => 'nullable|date',
            'image_path' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image_path')) { //finds name = this key in the .blade.php
            $validated['image_path'] =
                $request->file('image_path')->store('items', 'public');
                
        }

        $validated['user_id'] = auth()->id();

        Item::create($validated);

        return redirect() //goes back to homepage if user posts successfully
            ->route('index')
            ->with('success', 'Item posted successfully!');
    }

    public function index()
{
    if (Auth::check() && Auth::user()->IsAdmin()) {
        // Admin sees everything
        $items = Item::latest()->get();
    } 

    else {
    $items = Item::with('user:id,username,full_name')
    ->where('status', '!=', 'resolved') // ← hides item that status value is resolved.
    ->latest()
    ->get();
    }
    return response()->json($items);
}

public function destroy(Item $item) //delete
{
    //abort_unless(auth()->user()->IsAdmin(), 403);
    abort_unless(auth()->id() || auth() -> user() ->IsAdmin() === $item->user_id, 403);

    $item->delete();

    return response()->json(['success' => true]);
}

public function resolve(Item $item)//updates the status to resolve 
{
    //abort_unless(auth()->user()->IsAdmin(), 403);
    abort_unless(auth()->id() === $item->user_id || auth() -> user() ->IsAdmin() , 403);

    $item->update(['status' => 'resolved']);

    return response()->json(['success' => true]);
}

public function edit(Item $item)
{
    abort_unless(auth()->id() === $item->user_id || auth() -> user() ->IsAdmin() , 403);

    return view('edit', compact('item'));
}


public function update(Request $request, Item $item) //when users decides to update his/her own post/s
{
    abort_unless(auth()->id() === $item->user_id || auth() -> user() ->IsAdmin() , 403);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string',
        'location' => 'nullable|string',
        'date_occurred' => 'nullable|date',
        'image_path' => 'nullable|image|max:5120',
    ]);

    if ($request->hasFile('image_path')) {
    //Handle old image if user has an image
    if ($item->image_path && \Storage::disk('public')->exists($item->image_path)) {
            \Storage::disk('public')->delete($item->image_path);
    }
    //Use this existing as image or new image if the uploader decides to replace it and replace the old code below as it won'r work
    // Store new image
    $validated['image_path'] =
    $request->file('image_path')->store('items', 'public');
    //$path = $request->file('image_path')->store('items', 'public');
    //$validated['image_path'] = $path;
}

    $item->update($validated);

    return redirect()
        ->route('index')
        ->with('success', 'Item updated successfully');
}

public function contact(Request $request, Item $item)
{ //basic contact... 
    abort_if(auth()->id() === $item->user_id, 403);

    $data = $request->validate([
        'message' => 'required|string',
        'contact_info' => 'required|string|max:255',
    ]);

    Contact::create([
        'item_id' => $item->id,
        'requester_id' => auth()->id(),
        'message' => $data['message'],
        'contact_info' => $data['contact_info'],
    ]);

    // 
    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully',
    ]);
}

}

