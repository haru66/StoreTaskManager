<?php
namespace App\Utils;

/**
 * AppUtility.
 */
class AppUtility
{
    /*
     エリアコード
    １ 北海道・東北
    ２ 関東・甲信越
    ３ 東海・北陸
    ４ 関西
    ５ 四国・九州
    ６ 通販・海外
     */


    /*
     * function 
     */
    public static function getDateText($d){
        if($d == null) return null;
        $d = strtotime($d);
        $w = date("w", $d);
        $week_name = array("日", "月", "火", "水", "木", "金", "土");

        return date("Y年m月d日" . "($week_name[$w])", $d);
    }

    public static function getTimeText($d){
        if($d == null) return null;
        return date("H時i分", strtotime($d));
    }

    public static function checkDate($date)
    {
        $formats = [
            'Y-m-d',
            'Y/m/d',
            'Ymd',
        ];
        foreach ($formats as $format){
            \DateTime::createFromFormat($format, $date);
            $result = \DateTime::getLastErrors();
            if(!$result['warning_count'] && !$result['error_count'])
            {
                return true;
            }
        }
        return false;
    }

    public static function getNextDate($date)
    {
        return date('Y-m-d', strtotime($date . ' +1 day'));
    }

    public static function getPreviousDate($date)
    {
        return date('Y-m-d', strtotime($date . ' -1 day'));
    }

    public static function checkPast($date){
        $today = date("Y-m-d");

        /*if(strtotime($today) === strtotime($date)){
            echo "ターゲット日付は今日です";
        }else if(strtotime($today) > strtotime($date)){
            echo "ターゲット日付は過去です";
        }else{
            echo "ターゲット日付は未来です";
        }*/

        if(strtotime($today) > strtotime($date)){
            //echo "ターゲット日付は過去です";
            return true;
        }else{
            //echo "ターゲット日付は未来です";
            return false;
        }
    }

    public static function checkFuture($date){
        $today = date("Y-m-d H:i");

        if(strtotime($today) > strtotime($date)){
            //echo "ターゲット日付は過去です";
            return false;
        }else{
            //echo "ターゲット日付は未来です";
            return true;
        }
    }

    public static function checkToday($date){
        $today = date("Y-m-d");

        if(strtotime($today) === strtotime($date)){
            return true;
        }

        return false;
    }

    public static function getRoleText($role){
        if($role == 1) return "アルバイト";
        if($role == 2) return "社員";
        if($role == 3) return "マネージャー";
        return "誰かわからん";
    }
}