<?php
namespace app\includes;

class Common_fun {
    public static function depart_arr2jstr($arr){
        $str = '[';
        $i = 1;
        foreach ($arr as $key => $value) {
            $value_len = count($arr);
            $str .= '{';
            $str .= 'id:"'.$value['id'].'",';
            $str .= 'tags:"'.$value['id'].'",';
            $str .= 'text:"'.$value['text'].'"';
            // $str .= 'icon:"glyphicon glyphicon-folder-close",';
            // $str .= 'selectedIcon:"glyphicon glyphicon-folder-open",';
            // if ($value['id'] == 1) {
            //     $str .= 'state: {selected: true},'; 
            // }
            
            // $str .= 'selectable:true';
            if (isset($value['children'])) {
                $str .= ',children:'.self::depart_arr2jstr($value['children']);
                $str .= ',nodes:'.self::depart_arr2jstr($value['children']);
            }
            if ($i == $value_len) {
                $str .= '}';
            }else{
                $str .= '},' ;
            }
            $i++;
        }
        $str .= ']';
        return $str;
    }

    public static function module_arr2jstr($arr){
        $str = '[';
        $i = 1;
        foreach ($arr as $key => $value) {
            $value_len = count($arr);
            $str .= '{';
            $str .= 'id:"'.$value['id'].'",';
            $str .= 'tags:"'.$value['id'].'",';
            $str .= 'text:"'.$value['text'].'"';
            //$str .= 'icon:"glyphicon glyphicon-folder-close",';
            //$str .= 'selectedIcon:"glyphicon glyphicon-folder-open",';
            // if ($value['id'] == 1) {
            //     $str .= 'state: {selected: true,expanded: true},'; 
            // }
            //$str .= 'state: {expanded: false}'; 

            if (isset($value['children'])) {
                $str .= ',children:'.self::depart_arr2jstr($value['children']);
                $str .= ',nodes:'.self::depart_arr2jstr($value['children']);
            }

            if ($i == $value_len) {
                $str .= '}';
            }else{
                $str .= '},' ;
            }
            $i++;
        }
        $str .= ']';
        return $str;
    }


    public static function create_sn($model,$float_len = 5){

        $sn = $model::getSimpleCode();
        $sn = $sn.date('Ymd');

        $model_ob = $model::find()->where(['like','order_sn',$sn])->orderby('id DESC')->one();
        
        if ($model_ob) {
            $float_sn = intval(substr($model_ob->order_sn, -$float_len));
            $sn = $sn.str_repeat('0',$float_len-strlen($float_sn+1)).($float_sn+1);
            return $sn;
        }else{
            return $sn.'00001';
        }
    }

    public static function get_goods_store($goods_id){
        $query = new \yii\db\Query();
        $store_info = $query->select('s.store_id,st.store_name,sum(s.number) as stock_number')
                            ->from('stock as s')
                            ->leftjoin('store as st','st.id = s.store_id')
                            ->where(['s.goods_id'=>$goods_id])
                            ->groupby('s.store_id')
                            ->all();
        $store_number_total = 0;
        if ($store_info) {
            foreach ($store_info as $key => $value) {
                $store[$value['store_id']] = $value['store_name'].'/'.$value['stock_number'];
                $store_number_total += $value['stock_number'];
            }
            $store[0] = '总库存/'.$store_number_total;
            return $store;
        }else{
            return false;
        }
    }

}
?>