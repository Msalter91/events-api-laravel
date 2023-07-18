<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{

  use CanLoadRelationships;

  private array $relations = ['user', 'event'];

    public function index(Event $event)
    {
        $query = $this->LoadRelationships(Attendee::query());
        $attendees = $query->latest();

        return AttendeeResource::collection($attendees->paginate());
    }

    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
          'user_id' => 2
        ]);

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)
    {   
        return new AttendeeResource($this->LoadRelationships($attendee));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($event, Attendee $attendee)
    {
        $attendee->delete();
        return response(status: 204); 
    }
}
