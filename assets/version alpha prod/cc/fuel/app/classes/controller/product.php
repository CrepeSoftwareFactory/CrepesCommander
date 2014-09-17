<?php

class Controller_Product extends Controller_Template 
{
    public function action_index()
    {
        $presenter = Presenter::forge('product/index');
        $this->template->content = $presenter;
    }
    
    public function action_add()
    {
        try {
            if (Input::method() == 'POST') {
                $product = new Model_Product(Input::post());
                if (!$product->save()) {
                    throw new Exception($product->validation()->show_errors());
                }
                
                Response::redirect('product');
            }
        } catch (Exception $ex) {
            Session::set_flash('errors', $ex->getMessage());
        }
        
        $this->template->content = Presenter::forge('product/add');
    }
}

