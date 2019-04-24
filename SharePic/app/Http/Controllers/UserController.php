<?php

    namespace App\Http\Controllers;
    use App\Providers\UserDatabase;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Input;
    use App\User; 
    use Validator;
    use DB;
	
Class UserController extends Controller
{
    public $successStatus = 200;

    //Afficher les users
    function getUsers()
    {
        $db = new UserDatabase();
        $lol = $db->getTest();
        return response()->json($lol);
    }

    //Rechercher un user via Name
    function getUserByName($name)
    { 
        $name = $name."%";
        $user = DB::table('users')->where('name', 'like', $name)->get();

        if(empty($user))
        {
            return response()->json(['message'=>'Nobody found with this name']);
        }
        return response()->json($user);
    }

    //Get User via son Id
    function getUserById($id)
    {
        $user = DB::table('users')->where('id', '=', $id)->get();

        if(empty($user)){
            return response()->json(['message'=>'Nobody found with this Id']);
        }
        return response()->json($user);
    }

    //Register un User 
    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255|unique:users',  
            'password' => 'required|string|min:8', 
            'c_password' => 'required|same:password',
            'image' => 'mimes:jpeg,jpg,png|max:10000'
        ]);

        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $dest=public_path().'/images/pp/';
        $generique=public_path().'/images/pp/default.jpg';
        $input = $request->all(); 
        if(!empty($input['image']))
        {
        $img = $input['image'];
        $input['image']->move($dest, time() . $img->getClientOriginalExtension());
        }
        $input['password'] = bcrypt($input['password']); 
        $input['c_password'] = bcrypt($input['c_password']);
        if(empty($input['image']))
        {
            $input['image'] = $generique;
        }
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user;
        return response()->json(['success'=>$success], $this->successStatus); 
    }

    //Log un User
    function login(Request $request)
    { 
        $pass = hash('sha256', request('password'));
    

        if(Auth::attempt(['name' =>request('name'), 'password' =>request('password')]))
        { 
            $user = Auth::user();  
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
            return response()->json(['success' => $success], $this->successStatus); 
        } 
        else
        {
        return response()->json(['error'=>'Unauthorised'], 401); 
        }
    }

    //Logout
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['succes'=>true, 'status'=>200]);
    }

    function follow($followed)
    {
        $follower = Auth::user()->id;
        DB::table('subscription')
        ->insert(['follower'=>$follower,
        'followed'=>$followed
        ]);
        $success= "follow";
        return response()->json(['success'=>$success, 'status'=>200]); 
    }

    function unfollow($unfollowed)
    {
        $unfollower = Auth::user()->id;
        (int)$unfollowed;
        DB::table('subscription')->where([['follower', $unfollower], ['followed', $unfollowed]])->delete();
        return response()->json(['message'=>'élément supprimé', 'status'=>200]);
    }

    function getFollower()
    {
        $id_user = Auth::user()->id;
        //$query =  DB::raw("SELECT * FROM Users INNER JOIN subscription ON Users.id = subscription.followed WHERE subscription.followed ="." ".$id_user);
        $users = DB::table('Users')
            ->join('subscription', 'users.id', '=', 'subscription.followed')
            ->where('subscription.followed', '=' ,$id_user)
            ->select('users.id', 'users.name', 'users.image')
            ->get();
        return response()->json($users);
    }

    function getFollowed()
    {
        $id_user = Auth::user()->id;
        //$query = DB::raw("SELECT * FROM Users INNER JOIN subscription ON Users.id = subscription.followed WHERE subscription.follower ="." ".$id_user);
        $users = DB::table('Users')
            ->join('subscription', 'users.id', '=', 'subscription.followed')
            ->where('subscription.follower', '=' ,$id_user)
            ->select('users.id', 'users.name', 'users.image')
            ->get();
        return response()->json(["followed" => $users]);
    }
}