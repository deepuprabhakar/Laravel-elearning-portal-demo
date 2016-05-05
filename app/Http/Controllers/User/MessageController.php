<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Student;
use App\Http\Requests\MessageRequest;
use Auth;
use App\Message;
use Session;
use Sentinel;
use Hashids;
use App\User;
use DB;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['sentinel.auth', 'history']);
        $this->middleware('sentinel.role:user');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Sentinel::getUser();
        $messages = User::find($user->id)->messages()->with('user')->latest()->get()->toArray();
        $count = User::find($user->id)->messages()->where('status', 0)->count();
        return view('user.inbox', compact('messages', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $names = Student::all()->lists('name', 'user_id')->toArray();
        $count = User::find(Sentinel::getUser()->id)->messages()->where('status', 0)->count();
        return view('user.createMessage', compact('names', 'count'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MessageRequest $request)
    {
        $input = $request->all();
        $role = Sentinel::findRoleBySlug('admin');
        $role_id = DB::table('role_users')->where('role_id', $role->id)->first();
        $admin = Sentinel::findById($role_id->user_id)->toArray();
        $input['to'] = $admin['id'];
        $input['sender'] = Sentinel::getUser()->id;
        Message::create($input);
        Session::flash('success', 'Message sent.');
        return redirect(route('messages.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Hashids::connection('message')->decode($id);
        $messages = Message::find($id)->first();
        $user = $messages->user()->first()->toArray();
        $messages['status'] = 1;
        $messages->save();
        $count = User::find(Sentinel::getUser()->id)->messages()->where('status', 0)->count();
        return view('user.viewMessage', compact('messages', 'user', 'count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Hashids::connection('message')->decode($id);
        Message::destroy($id);
        return redirect()->route('messages.index')->with('success', 'Message deleted successfully');
    }
    /**
     * view sent messages
     *
     */
    public function sent()
    {
        $user = Sentinel::getUser();
        $messages = User::find($user->id)->sent()->with('sender')->latest()->get()->toArray();
        $count = User::find($user->id)->messages()->where('status', 0)->count();
        return view('user.sent', compact('messages', 'count'));
    }

    /**
     * 
     * view sent messages individual
     *
     * @param      <type>  $id     (description)
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function sentmessages($id)
    {
       $id = Hashids::connection('message')->decode($id);
       $messages = Message::find($id)->first();
       $user = $messages->user()->first();
       $count = User::find(Sentinel::getUser()->id)->messages()->where('status', 0)->count();
       return view('user.viewSentMessage', compact('messages', 'user', 'count'));
    }

    /**
     * Destroy inbox messages
     */
    public function destroyMany(Request $request)
    {
        $ids = $request->get('message-check');
        if(empty($ids))
            return redirect()->back()->with('error', 'Nothing to delete here!');
        foreach ($ids as $id => $value) {
            $ids[$id] = Hashids::connection('message')->decode($value);
        }
        Message::destroy($ids);
        return redirect()->back()->with('success', 'Message deleted!');
    }
    /**
     * destroy sent message
     *
     * @param      <type>  $id     (description)
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function destroySent($id)
    {
        $id = Hashids::connection('message')->decode($id);
        Message::destroy($id);
        return redirect()->route('messages.sent')->with('success', 'Message deleted successfully');
    }
    /**
     * { function_description }
     *
     * @param      <type>  $id     (description)
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function reply(MessageRequest $request)
    {
        $input = $request->all();
        $id = Hashids::connection('message')->decode($input['to']);
        $input['to'] = $id[0];
        $input['sender'] = Sentinel::getUser()->id;
        Message::create($input);
        Session::flash('success', 'Message sent.');
        return redirect()->back();
    }
}