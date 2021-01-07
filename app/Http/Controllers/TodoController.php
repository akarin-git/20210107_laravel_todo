<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Models\Todo;
// ユーザーid
use Auth;

class TodoController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth']);
  }
    
     public function index()
    {
  // モデルに定義した関数を実行する．
    $todos = Todo::getMyAllOrderByDeadline();
    //  ddd($todos);
    return view('todo.index', [
    'todos' => $todos
    ]);
    }
        
       public function create()
        {
    // 追記
         return view('todo.create');
        }


    
    public function store(Request $request)
    {
        // todo中身
         // ddd($request->all());
        // ユーザーid
        // ddd(Auth::user());
        // ddd(Auth::user()->id);
  // バリデーション
    $validator = Validator::make($request->all(), [
        'todo' => 'required | max:191',
        'deadline' => 'required',
        ]);
        
        // var_dump($request);
        // exit();
  // バリデーション:エラー
    if ($validator->fails()) {
        return redirect()
        ->route('todo.create')
        ->withInput()
        ->withErrors($validator);
    }
    // フォームから送信されてきたデータとユーザIDをマージする
    // $requestにuser_idがmerge
        $data = $request->merge(['user_id' => Auth::user()->id])->all();
        // ddd($data);
        // $requestにuser_id含まれてクリエイトしてくれる
        
  // create()は最初から用意されている関数
  // 戻り値は挿入されたレコードの情報
        $result = Todo::create($data);
  // ルーティング「todo.index」にリクエスト送信（一覧ページに移動）
        return redirect()->route('todo.index');
    }
        

    
   public function show($id)
    {
    $todo = Todo::find($id);
    return view('todo.show', ['todo' => $todo]);
    }
    
     
    public function edit($id)
    {
        // ddd($id);
        $todo = Todo::find($id);
        return view('todo.edit', ['todo' => $todo]);
    }

   
    public function update(Request $request, $id)
    {
        
        // ddd($id);
        // ddd($request->all())
         //バリデーション
    $validator = Validator::make($request->all(), [
        'todo' => 'required | max:191',
        'deadline' => 'required',
    ]);
  //バリデーション:エラー
    if ($validator->fails()) {
        return redirect()
        ->route('todo.edit', $id)
        ->withInput()
        ->withErrors($validator);
    }
  //データ更新処理
  // updateは更新する情報がなくても更新が走る（updated_atが更新される）
  $result = Todo::find($id)->update($request->all());
//   ddd($result);
  // fill()save()は更新する情報がない場合は更新が走らない（updated_atが更新されない）
  // $redult = Todo::find($id)->fill($request->all())->save();
  return redirect()->route('todo.index');
    }

    
    public function destroy($id)
    {
        
        // dd($id);
    $result = Todo::find($id)->delete();
    // ddd($result);
    return redirect()->route('todo.index');
    }
}
