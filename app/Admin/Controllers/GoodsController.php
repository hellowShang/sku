<?php

namespace App\Admin\Controllers;

use App\Model\GoodsModel;
use App\Model\StoreModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Model\GoodsModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoodsModel);

        $grid->column('goods_id', __('商品编号'));
        $grid->column('goods_name', __('商品名称'));
        $grid->column('self_price', __('销售价格'));
        $grid->column('market_price', __('市场价格'));
        $grid->column('goods_num', __('库存'));
        $grid->column('goods_score', __('积分'));
        $grid->column('goods_desc', __('简介'));
        $grid->column('is_up', __('是否上架'))->display(function($is_up){
            if($is_up == 1){
                return "上架";
            }else{
                return "未上架";
            }
        });
        $grid->column('goods_img', __('商品图片'))->display(function($goods_img){
                return $goods_img;
        })->image('http://www.lab993.com/uploads/goodsimgs/',40,40);
        $grid->column('store', __('所属店铺'))->display(function($store){
            $arr = StoreModel::where('id',$store)->first()->toArray();
            return $arr['name'];
        });
        $grid->column('create_time', __('添加时间'));
        $grid->column('update_time', __('修改时间'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(GoodsModel::findOrFail($id));

        $show->field('goods_id', __('商品编号'));
        $show->field('goods_name', __('商品名称'));
        $show->field('self_price', __('销售价格'));
        $show->field('market_price', __('市场价格'));
        $show->field('goods_num', __('库存'));
        $show->field('goods_score', __('积分'));
        $show->field('goods_desc', __('简介'));
        $show->field('is_up', __('是否上架'));
        $show->field('goods_img', __('商品图片'));
        $show->field('store', __('所属店铺'));
        $show->field('create_time', __('添加时间'));
        $show->field('update_time', __('修改时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GoodsModel);

        $form->text('goods_name', __('商品名称'));
        $form->decimal('self_price', __('销售价格'));
        $form->decimal('market_price', __('市场价格'));
        $form->number('goods_num', __('库存'));
        $form->number('goods_score', __('积分'));
        $form->textarea('goods_desc', __('商品简介'));
        $form->switch('is_up', __('是否上架'));
        $form->file('goods_img', __('商品图片'));
        $form->text('store', __('所属店铺'));
        $form->number('create_time', __('添加时间'));
        $form->number('update_time', __('修改时间'));

        return $form;
    }
}
