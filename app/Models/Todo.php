<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Todo extends Model
{
  use HasFactory;

  // アプリケーション側でcreateなどできない値を記述
  // ↓以下の処理を記述
  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

public static function getAllOrderByDeadline()
{
  $todos = self::orderBy('deadline', 'asc')->get();
  return $todos;
}

public static function getMyAllOrderByDeadline()
  {
    $todos = self::where('user_id', Auth::user()->id)
      ->orderBy('deadline', 'asc')
      ->get();
    return $todos;
  }


}