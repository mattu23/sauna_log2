<?php 

namespace App\Services;

use App\Models\Saunalog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;



class SaunalogService 
{

    //ユーザーに紐づくサウナログ一覧の表示
    public function getLogsByUser($userId): Collection 
    {
      try {
          return Saunalog::where('userId', $userId)->get();
      } catch(\Exception $e) {
          throw new \App\Exceptions\CustomException('ユーザーのサウナログ取得中にエラーが発生しました。', 500, $e);
      }
    }


    //特定のサウナログの表示
    public function getLogById($id): ?Saunalog
    {
      try {
          $saunalog = Saunalog::find($id);
          if(!$saunalog) {
            throw new \App\Exceptions\NotFoundException('指定されたサウナログが見つかりません。', 404);
          }
          return $saunalog;
      } catch(\Exception $e) {
          throw new \App\Exceptions\CustomException('サウナログの取得中にエラーが発生しました。', 500, $e);
      }
    }

    //サウナログの新規作成
    public function createLog(array $data, User $user): Saunalog
    {
      try {
          $saunalog = new Saunalog($data);
          $saunalog->user()->associate($user);
          $saunalog->save();
          return $saunalog;
      } catch(\Exception $e) {
          throw new \App\Exceptions\CustomException('サウナログの作成に失敗しました。', 500, $e);
      }
    }

    //サウナログの編集
    public function updateLogById($id, array $data): Saunalog
    {
      try {
          $saunalog = Saunalog::find($id);
          if(!$saunalog) {
            throw new \App\Exceptions\NotFoundException('編集対象のサウナログが見つかりません。', 404);
          }
          $saunalog->update($data);
          return $saunalog;
      } catch(\Exception $e) {
          throw new \App\Exceptions\CustomException('サウナログの編集に失敗しました。', 500, $e);
      }
    }

    //サウナログの削除
    public function deleteLogById($id): bool
    {
      try {
          $saunalog = Saunalog::find($id);
          if (!$saunalog) {
            throw new \App\Exceptions\NotFoundException('削除対象のサウナログが見つかりません。', 404);
         }
          return $saunalog->delete();
      } catch(\Exception $e) {
        throw new \App\Exceptions\CustomException('サウナログの削除に失敗しました。', 500, $e);
      } 
    }


}