<?
namespace App\Http\Traits;
 
 use App\Models\Setting;
  
 trait GlobalTrait {
      
    function filter_tiltle($str)
    {
        return  str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|",","), "-", $str);
    }
}
?>