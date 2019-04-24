<?php
        namespace App\Http\Controllers;

        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;
        use Validator;
        use App\Image;
        use DB;
        
    Class ImageController extends Controller
    {
        public $successStatus = 200;

        function store(Request $request)
        {
            $id_user = Auth::user()->id;

            $validator = Validator::make($request->all(), [ 
                'image' => 'mimes:jpeg,jpg,png|max:10000'
            ]);

            if($validator->fails())
            {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            $date = date("Y-m-d H:i:s");
            $dest=public_path().'/images/store/';
            $generique=public_path().'/images/store/1554451530.png';
            $input = $request->all(); 
            if(!empty($input['image']))
            {
                $input['image']->move($dest, time().'.'.$input['image']->getClientOriginalExtension());
            }
            else
            {
                $input['image']= $generique;
            }
            DB::table('image')
            ->insert(['image' => $input['image'],
            'User_id'=>$id_user,
            'description' => $input['description'],
            'localisation' => $input['localisation'],
            'like' => 0,
            'date' => $date]);
            $success= $input['description'];
            return response()->json(['success'=>$success, 'status'=>200]); 
        }

        function getStore()
        {
            $img=DB::table('image')->orderBy('date', 'DESC')->get();
            return response()->json($img);
        }

        function deleteStore($id)
        {
            dump(DB::table('image')->where('idImage', $id)->delete());
            return response()->json(['message'=>'élément supprimé', 'status'=>200]);
        }

        function addComment(Request $request, $id)
        {
            $id_user = Auth::user()->id;
            $input = $request->all();
            $date = date("Y-m-d H:i:s");
            DB::table('comment')
            ->insert([
            'description' => $input['description'],
            'User_id' => $id_user,
            'Image_idImage' => $id,
            'date' => $date]);
            $success= $input['description'];
            return response()->json(['success'=>$success, 'status'=>'200']); 
        }

        function deleteComment()
        {
            $id_user = Auth::user()->id;
            dump(DB::table('comment')->where('User_id', $id_user)->delete());
            return response()->json(['message'=>'élément supprimé', 'status'=>200]);
        }
        
        function like($id)
        {
             DB::raw("UPDATE Image SET like = like + 1 WHERE idImage ="." ".$id);
             return response()->json(['success'=>200]);
        }

        function dislike($id)
        {
             DB::raw("UPDATE Image SET like = like - 1 WHERE idImage ="." ".$id);
             return response()->json(['success'=>200]);
        }

        function getImageUser()
        {
            $id_user = Auth::user()->id;
            $users = DB::table('users')
            ->join('image', 'users.id', '=', 'image.User_id')
            ->where('image.User_id', '=' , $id_user)
            ->select('users.name', 'users.image', 'image.image', 'image.description', 'image.localisation', 'image.date')
            ->get();
            return response()->json($users);
        }

        function getImageFollower()
        {
            $id_user = Auth::user()->id;
            $users = DB::table('users')
                ->join('subscription', 'users.id', '=', 'subscription.followed')
                ->join('image', 'users.id', '=', 'image.User_id')
                ->where('subscription.follower', '=' ,$id_user)
                ->select('users.name', 'image.image', 'image.description', 'image.localisation', 'image.date')
                ->orderBy('date', 'DESC')
                ->get();
                return response()->json($users);
        }

        function getComment($id)
        {
            $comment = DB::table('comment')
            ->join('users', 'users.id', '=', 'comment.User_id')
            ->join('image', 'image.idImage', '=', 'comment.Image_idImage')
            ->where('image.idImage', '=', $id)
            ->select('image.image', 'image.description', 'comment.description')
            ->get();
            return response()->json($comment);
        }

        function getImageUtilisateur($id)
        {
            $users = DB::table('users')
            ->join('image', 'users.id', '=', 'image.User_id')
            ->where('image.User_id', '=' , $id)
            ->select('users.name', 'users.image', 'image.image', 'image.description', 'image.localisation', 'image.date')
            ->get();
            return response()->json($users);
        }
    }