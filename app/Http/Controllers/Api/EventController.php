<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRealtionships;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{

    // our custome trate
    use CanLoadRealtionships;

    private array $relations = ['user', 'attendees', 'attendees.user'];



    public function __construct()
    {
        // add a middleware to enable authentication to verify if the user signed in
        // and to get the current user
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        // define the policy
        // the seconed argument is the parameter name on the routes check route:list
        // this will make sure that every method from the policy will be called before a specific action
        $this->authorizeResource(Event::class, 'event');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Event::all();

        // instead of this will use the api resource to return data 
        // the EventResource we cereated it using terminal 
        //you can customize the responce into EventResource.php which it is the api resource here
        // return EventResource::collection(Event::all());

        // instead ot that will return event with user owner
        // return EventResource::collection(Event::with('user')->get());

        // return EventResource::collection(Event::with('user')->paginate());

        // -----------------------------------------------------------------

        // will return event with optional relation loaded
        // we created trait so will use loadRelationships() method from that trait


        // $query = $this->loadRelationships(Event::query(), $relations);

        // you dont have to use $relations as a seconed argument because it the method it will check if the private method we add it in the top of class
        //  if it there or not and if there it will use it automatically
        // but you can add it manully like in the line above
        $query = $this->loadRelationships(Event::query());



        return EventResource::collection(
            $query->latest()->paginate()
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => $request->user()->id
        ]);

        // return $event;

        // here return with api resource to customize the responce
        // return new EventResource($event);

        // here return with optional relation and api resource
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // return $event;

        // return new EventResource($event);

        // you can use load() to get the relation and you can return data without have to use the line under
        // $event->load('user');
        // return new EventResource($event);

        // $event->load('user', 'attendees');
        // return new EventResource($event);

        // instead of loading relatinships in the way in the line above will use the method in trait
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // // sometimes:it will check from the value just if the data present in the input
        // $event->update($request->validate([
        //     'name' => 'sometimes|string|max:255',
        //     'description' => 'nullable|string',
        //     'start_time' => 'sometimes|date',
        //     'end_time' => 'sometimes|date|after:start_time'
        // ]));

        // // return $event;

        // return new EventResource($this->loadRelationships($event));

        // __________________________________________

        // update with gate authorized
        // so user can update just their own event

        // if (Gate::denies('update-event', $event)) {
        //     // when the gate denies will return abort
        //     // 403: when the action is frobbiden
        //     abort(403, 'you are not authorized to update this event');
        // }

        // this is a shortcut to verify Gate with Gate name  like in the line above
        // $this->authorize('update-event', $event);
        // we dont have call this line above after using policies


        $event->update($request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time'
        ]));

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        // return response()->json([
        // 'message'=>'event deleted successfully'
        // ]);

        // when you delete resources you have to dont send body response (no content)
        // you can send the http code 204 it meant deleted
        return response(status: 204);
    }
}
