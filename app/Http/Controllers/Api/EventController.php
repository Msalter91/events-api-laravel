<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

  use CanLoadRelationships;

  private array $relations = ['user', 'attendees', 'attendees.user'];

    public function index()
    {   
      $query = $this->LoadRelationships(Event::query());
        return EventResource::collection($query->latest()->paginate());
    }

    public function store(Request $request)
    {
        $request->validate(
          [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
          ]
          );

          $event = Event::create([
            ...$request->validate(
              [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
              ]
              ), 
              'user_id' => 1
            ]);

            return $this->LoadRelationships($event);
    }

    public function show(Event $event)
    {
      $event->load('user');
      $event->load('attendees');
        return new EventResource($this->LoadRelationships($event));
    }

    public function update(Request $request, Event $event)
    {
      $event->update(
        $request->validate(
          [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time'
          ]
          )
      );
      
          return new EventResource($this->LoadRelationships($event));
    }

    public function destroy(Event $event)
    {
      $event->delete();
      return response(status: 204);
    }
}
