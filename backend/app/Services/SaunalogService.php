<?php 

namespace App\Services;

use App\Models\Saunalog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;




class SaunalogService 
{

    //ユーザーに紐づくサウナログ一覧の表示
    public function getLogsByUser($userId): Collection 
    {
        $logs = Saunalog::where('userId', $userId)->get();
        //「ログが見つからない＝Whereメソッドが空」なので、そのチェックをし空の場合は例外を投げる
        if($logs->isEmpty()) {
          throw new NotFoundException('ユーザーに紐づくサウナログが見つかりません。');
        }
        return $logs;
    }


    //特定のサウナログの表示
    public function getLogById($id): ?Saunalog
    {
        $saunalog = Saunalog::find($id);
        //findメソッドに対し例外は発生しないため、NotFoundのみで表記
        if(!$saunalog) {
          throw new NotFoundException('特定のサウナログが見つかりません。');
        }
        return $saunalog;
    }

    //サウナログの新規作成
    public function createLog(array $data, User $user): Saunalog
    {
        $saunalog = new Saunalog($data);
        $saunalog->user()->associate($user);
        if($saunalog->save()) {
          return $saunalog;
        } else {
          throw new SystemException('ログの新規作成に失敗しました。');
        }
    }

    //サウナログの編集
    public function updateLogById($id, array $data): Saunalog
    {
        $saunalog = Saunalog::find($id);
        //findメソッドに対し例外は発生しないため、NotFoundのみで表記
        if(!$saunalog) {
          throw new NotFoundException('編集対象のサウナログが見つかりません。');
        }
        $saunalog->update($data);
        return $saunalog;
    }

    //サウナログの削除
    public function deleteLogById($id): bool
    {
        $saunalog = Saunalog::find($id);
        //findメソッドに対し例外は発生しないため、NotFoundのみで表記
        if (!$saunalog) {
          throw new NotFoundException('削除対象のサウナログが見つかりません。');
        }
        return $saunalog->delete();
    }

}