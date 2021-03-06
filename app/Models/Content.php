<?php

namespace App\Models;

use http\Exception;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Content extends Model
{
    protected $table = "content";
    public $timestamps = true;
    protected $primaryKey = 'nb_id';
    protected $guarded = [];

    /**
     * 插入数据到表
     * @auther ZhongChun <github.com/RobbEr929>
     * @param [string]$zc
     */
    public static function zc_insert($zc)
    {
        try {
            $zc['created_at'] = Carbon::now()->toDateTimeString();
            $zc['updated_at'] = $zc['created_at'];
            self::insert([
                'title' => $zc['title'],
                'p_url' => $zc['p_url'],
                'priority' => $zc['priority'],
                'neirong' => $zc['neirong'],
                'created_at' => $zc['created_at'],
                'updated_at' => $zc['updated_at']
            ]);
            return true;
        } catch (\Exception $e) {
            logError('填报错误', [$e->getMessage()]);
        }
    }

    /**
     * 获取当前表的nb_id
     * @auther ZhongChun <github.com/RobbEr929>
     * return int
     */
    public static function zc_getid(){
        try {
            $id = self::where('nb_id','>=',1)->max('nb_id');
            return $id;
        }catch (\Exception $e){
            LogError('查找失败',[$e->getMessage()]);
        }
    }

    /**
     * 当关联的表插入失败时 删除所对应关联的表
     * @auther ZhongChun <github.com/RobbEr929>
     * @param $nb_id
     */
    public static function zc_delete($nb_id){
        try {
            self::where('labor_id',$nb_id)
                ->delete();
            return true;
        }catch (\Exception $e){
            LogError('删除失败',[$e->getMessage()]);
        }
    }


    /**
     * 根据优先级返回 id,标题
     * @author tangbangyan <github.com/doublebean>
     * @return mixed
     */
    public static function tby_getRotationPicture()
    {
        try{
            $date=Content::select('nb_id','p_url')
               ->orderby('priority','asc')
                ->take(4)
               ->get();
            return $date;
        }catch(Exception $e){
            logger::Error('没找到该图片',[$e->getMessage()]);
        }
    }


    /**
     * 根据优先级返回前6个新闻标题，时间
     * @author tangbangyan <github.com/doublebean>
     * @return mixed
     */
    public static function tby_getLabTitle()
    {
        try{
            $date=self::select('created_at','title')
                ->orderby('priority','asc')
                ->take(6)
                ->get();

            return $date;
        }catch(Exception $e){
            logger::Error('没找到该图片',[$e->getMessage()]);
        }
    }
   /**
     * 传入title数据，返回新闻公告具体内容
    * @author tangbangyan <github.com/doublebean>
     * @param $title
     * @return mixed
     */
    public static function tby_getLabNew($title)
    {
        try{
            $date=self::where('title',$title)
                ->first();

            return $date;
        }catch(Exception $e){
            logger::Error('没找到该图片',[$e->getMessage()]);
        }
    }
   /**
     * 根据优先级返回内容，图片，标题，时间
    * @author tangbangyan <github.com/doublebean>
     * @return mixed
     */
    public static function tby_getlabnewcontent()
    {
        try{
            $date=self::select('title','neirong','p_url','created_at')
                ->orderby('priority','asc')
                ->get();

            return $date;
        }catch(Exception $e){
            logger::Error('没找到该图片',[$e->getMessage()]);
        }
    }
}
