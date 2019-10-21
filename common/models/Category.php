<?php

namespace app\common\models;

use Yii;
use yii\db\Query;


class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort_order', 'show_in_nav', 'is_show', 'grade', 'is_package'], 'integer'],
            [['style', 'pic'], 'required'],
            [['cat_name'], 'string', 'max' => 90],
            [['keywords', 'cat_desc', 'filter_attr'], 'string', 'max' => 255],
            [['template_file'], 'string', 'max' => 50],
            [['measure_unit'], 'string', 'max' => 15],
            [['style'], 'string', 'max' => 150],
            [['pic'], 'string', 'max' => 233]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'Cat ID',
            'cat_name' => 'Cat Name',
            'keywords' => 'Keywords',
            'cat_desc' => 'Cat Desc',
            'parent_id' => 'Parent ID',
            'sort_order' => 'Sort Order',
            'template_file' => 'Template File',
            'measure_unit' => 'Measure Unit',
            'show_in_nav' => 'Show In Nav',
            'style' => 'Style',
            'is_show' => 'Is Show',
            'grade' => 'Grade',
            'filter_attr' => 'Filter Attr',
            'is_package' => 'Is Package',
            'pic' => 'Pic',
        ];
    }

   /**
     * 获得指定分类下的子分类的数组
     *
     * @access  public
     * @param   int     $cat_id     分类的ID
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @param   int     $is_show_all 如果为true显示所有分类，如果为false隐藏不可见分类。
     * @return  mix
     */
    public function cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0, $is_show_all = true)
    {   
 
        $query = new \yii\db\Query();
        $cat_list  =  $query->select('c.cat_id, c.cat_name, c.measure_unit, c.parent_id, c.sort_order, COUNT(s.cat_id) AS has_children')
                            ->from('category as c')
                            ->leftjoin('category as s','s.parent_id=c.cat_id')
                            ->groupby(['c.cat_id'])
                            ->orderby(['c.parent_id'=>SORT_ASC,'c.sort_order'=>SORT_ASC,])
                            ->all();
        
        $options = self::cat_options($cat_id, $cat_list); // 获得指定分类下的子分类的数组

        $children_level = 99999; //大于这个分类的将被删除
        if ($is_show_all == false)
        {
            foreach ($options as $key => $val)
            {
                if ($val['level'] > $children_level)
                {
                    unset($options[$key]);
                }
                else
                {
                    if ($val['is_show'] == 0)
                    {
                        unset($options[$key]);
                        if ($children_level > $val['level'])
                        {
                            $children_level = $val['level']; //标记一下，这样子分类也能删除
                        }
                    }
                    else
                    {
                        $children_level = 99999; //恢复初始值
                    }
                }
            }
        }

        if ($re_type == true)
        {
            $select = '';
            foreach ($options AS $var)
            {
                $select .= '<option value="' . $var['cat_id'] . '" ';
                $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
                $select .= '>';
                if ($var['level'] > 0)
                {
                    $select .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select .= htmlspecialchars(addslashes($var['cat_name']), ENT_QUOTES) . '</option>';
            }
            return $select;
        }
        else
        {   
            return $options;
        }
    }


    /**
     * 过滤和排序所有分类，返回一个带有缩进级别的数组
     *
     * @access  private
     * @param   int     $cat_id     上级分类ID
     * @param   array   $arr        含有所有分类的数组
     * @param   int     $level      级别
     * @return  void
    */

    private function cat_options($spec_cat_id, $arr)
    {
        static $cat_options = array();

        if (isset($cat_options[$spec_cat_id]))
        {
            return $cat_options[$spec_cat_id];
        }

        if (!isset($cat_options[0]))
        {
            $level = $last_cat_id = 0;
            $options = $cat_id_array = $level_array = array();

            while (!empty($arr))
            {
                foreach ($arr AS $key => $value)
                {
                    $cat_id = $value['cat_id'];
                    if ($level == 0 && $last_cat_id == 0)
                    {
                        if ($value['parent_id'] > 0)
                        {
                            break;
                        }

                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0)
                        {
                            continue;
                        }
                        $last_cat_id  = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['parent_id'] == $last_cat_id)
                    {
                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0)
                        {
                            if (end($cat_id_array) != $last_cat_id)
                            {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id    = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    }
                    elseif ($value['parent_id'] > $last_cat_id)
                    {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1)
                {
                    $last_cat_id = array_pop($cat_id_array);
                }
                elseif ($count == 1)
                {
                    if ($last_cat_id != end($cat_id_array))
                    {
                        $last_cat_id = end($cat_id_array);
                    }
                    else
                    {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id]))
                {
                    $level = $level_array[$last_cat_id];
                }
                else
                {
                    $level = 0;
                }
            }
            $cat_options[0] = $options;
        }
        else
        {
            $options = $cat_options[0];
        }

        if (!$spec_cat_id)
        {
            return $options;
        }
        else
        {
            if (empty($options[$spec_cat_id]))
            {
                return array();
            }

            $spec_cat_id_level = $options[$spec_cat_id]['level'];

            foreach ($options AS $key => $value)
            {
                if ($key != $spec_cat_id)
                {
                    unset($options[$key]);
                }
                else
                {
                    break;
                }
            }

            $spec_cat_id_array = array();
            foreach ($options AS $key => $value)
            {
                if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                    ($spec_cat_id_level > $value['level']))
                {
                    break;
                }
                else
                {
                    $spec_cat_id_array[$key] = $value;
                }
            }
            $cat_options[$spec_cat_id] = $spec_cat_id_array;
            return $spec_cat_id_array;
        }
    }
 

    public function cat_children_str($cat_id){
        $the_list = [$cat_id];

        $child_id_list = self::get_child_id_list($cat_id);
        $the_list = array_merge($the_list,$child_id_list);
        if (count($child_id_list) > 0) {
            foreach ($child_id_list as $key => $value) {
                 $child_id_list = self::cat_children_str($value);
                 if (count($child_id_list) > 0) {
                    $the_list = array_merge($the_list,$child_id_list);
                 }
            }
        }else{
            return $the_list;
        }
        return $the_list;
    }

    private function get_child_id_list($cat_id){
        if ($cat_id > 0) {
            $query = new \yii\db\Query();
            return $query->select('cat_id')->from('category')->where(['parent_id'=>$cat_id])->column();
        }else{
            return false;
        }
    }
    public function getName($cat_id){
        $query = new \yii\db\Query();
        $s=$query->select('cat_name')->from('category')->where(['cat_id'=>$cat_id])->one();
        return $s['cat_name'];
    }


}
