<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRealtionships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRealtionships;

    private array $relations = ['user'];


    // this construct to add a middleware to enable authentication to verify if the user signed in
    // and to get the current user
    public function __construct()
    {
        // add a middleware to enable authentication to verify if the user signed in
        // and to get the current user
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);

        // define the policy
        // the seconed argument is the parameter name on the routes check route:list
        // this will make sure that every method from the policy will be called before a specific action
        $this->authorizeResource(Attendee::class, 'attendee');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        // $attendees = $event->attendees()->latest();

        // return AttendeeResource::collection(
        //     $attendees->paginate()
        // );

        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        // $attendee = $this->loadRelationships(
        //     $event->attendees()->create([
        //         'user_id' => 1
        //     ])
        // );

        // return new AttendeeResource($attendee);

        // after we get the user from the request object we can use user id
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => $request->user()->id
            ])
        );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    // because we dont want want to return body responce we dont have to declare Event model 
    // but we put it cause we have to get the event in the link
    public function destroy(Event $event, Attendee $attendee)
    {
        // $attendee->delete();

        // return response(status: 204);

        // ______________________
        // with gate

        // $this->authorize('delete-attendee', [$event, $attendee]);

        $attendee->delete();

        return response(status: 204);
    }
}
