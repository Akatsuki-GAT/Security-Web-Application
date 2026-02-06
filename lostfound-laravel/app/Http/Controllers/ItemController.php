<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Contact;

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
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) { //buggy as of now 
            $validated['photo_url'] =
                $request->file('image')->store('items', 'public');
        }

        $validated['user_id'] = auth()->id();

        Item::create($validated);

        return redirect() //goes back to homepage if user posts successfully
            ->route('index')
            ->with('success', 'Item posted successfully!');
    }

    public function index()
{
    $items = Item::with('user:id,username,full_name')
    ->where('status', '!=', 'resolved') // ← hides item that status value is resolved.
    ->latest()
    ->get();
    return response()->json($items);
}

public function destroy(Item $item) //delete
{
    abort_unless(auth()->id() === $item->user_id, 403);

    $item->delete();

    return response()->json(['success' => true]);
}

public function resolve(Item $item)//updates the status to resolve 
{
    abort_unless(auth()->id() === $item->user_id, 403);

    $item->update(['status' => 'resolved']);

    return response()->json(['success' => true]);
}

public function edit(Item $item)
{
    abort_unless(auth()->id() === $item->user_id, 403);

    return view('edit', compact('item'));
}


public function update(Request $request, Item $item) //when users decides to update his/her own post/s
{
    abort_unless(auth()->id() === $item->user_id, 403);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string',
        'location' => 'nullable|string',
        'date_occurred' => 'nullable|date',
        'image' => 'nullable|image|max:5120',
    ]);

    if ($request->hasFile('image')) { //again still buggy on this part
        $validated['photo_url'] =
            $request->file('image')->store('items', 'public');
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

