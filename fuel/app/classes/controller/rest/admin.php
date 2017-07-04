<?php
class Controller_Rest_Admin extends Controller_Rest 
{
    public function post_changeNbPiles()
    {
        $nbPiles = Input::post('nbPiles');
        $stations = Model_Station::find();
        $nbStations = count($stations);
        if ($nbPiles) {
            try {
                if($nbPiles == $nbStations){
                    $response = array(
                        'error'       => true,  
                        'message'     => 'Même nombre de piles !',  
                    );
                }
                else if($nbPiles < $nbStations){
                    for($i=$nbStations; $i>$nbPiles; $i--){
                        $station = Model_Station::find_by_pk($stations[$i-1]->station_id);
                        $response = array(
                                'error'       => false,  
                                'message'     => $station,  
                            );
                        if (!$station->delete()) {
                            throw new Exception('Erreur lors de la suppression');
                        }
                    }
                    DB::commit_transaction();
                    $response = array(
                        'error'       => false,  
                        'message'     => 'Piles enlevées',  
                    );
                }
                else if($nbPiles > $nbStations){
                    $min = $nbStations+1;
                    for($i=$min; $i<=$nbPiles; $i++){
                        $station = new Model_Station(array(
                            'station_id'    => $i,
                            'name'      => 'P'.$i,
                            'state'         => 0,
                        ));
                        if (!$station->save()) {
                            throw new Exception($station->validation()->show_errors());
                        }
                    }
                    DB::commit_transaction();
                    $response = array(
                        'error'       => false,  
                        'message'     => 'Piles ajoutées',  
                    );
                }
            }catch (Exception $ex) {
                $response = array(
                    'error'       => true,  
                    'message'     => $ex->getMessage(),  
                );
            }
            return $this->response($response);
        }
    }
    
    public function post_changeTypeProduct(){
        $value = Input::post('value');
        $champ = Input::post('champ');
        $id = Input::post('id');
        try {
            $poductType = Model_Product_Type::find_by_pk($id);
            $poductType->$champ = $value;
            if (!$poductType->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            $response = array(
                'error'       => false,  
                'message'     => 'Modification apportée à la base !',  
            );
            Session::set_flash('success', 'Modification apportée à la base !');
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    public function post_supTypeProduct(){
         $typeId = Input::post('id');
        try 
        {
            $product = Model_Product_Type::find_by_pk($typeId);
            if (!$product->delete()) {
                throw new Exception('Erreur lors de la suppression');
            }
            else
            {
                
                $message = 'Le type a bien été supprimé';
                $delete_commande = false;
                
                DB::commit_transaction();
                
                $response = array(
                        'error'             => false,  
                        'message'           => $message, 
                        'delete_commande'   => $delete_commande,
                );
            }
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    public function post_addTypeProduct(){
        $nom = Input::post('nom');
        $couleur = Input::post('couleur');
        try {
            DB::start_transaction();
            $type = new Model_Product_Type();
            
            $type->set(array(
                'type_label'    => $nom,
                'type_couleur'  => $couleur,
            ));
            
            if (!$type->save()) {
                throw new Exception($type->validation()->show_errors());
            }
            
            DB::commit_transaction();
            
            $response = array(
              'error'       => false,  
              'message'     => 'Type de produit créé avec succès',  
            );
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }
    
    public function post_changeProduct(){
        $value = Input::post('value');
        $champ = Input::post('champ');
        $id = Input::post('id');
        try {
            $poductType = Model_Product::find_by_pk($id);
            $poductType->$champ = $value;
            if (!$poductType->save()) {
                throw new Exception($poductType->validation()->show_errors());
            }
            $response = array(
                'error'       => false,  
                'message'     => 'Modification apportée à la base !',  
            );
            Session::set_flash('success', 'Modification apportée à la base !');
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    public function post_supProduct(){
        $product_id = Input::post('id');
        try 
        {
            $product = Model_Product::find_by_pk($product_id);
            if (!$product->delete()) {
                throw new Exception('Erreur lors de la suppression');
            }
            else
            {
                
                $message = 'Le produit a bien été supprimé';
                $delete_commande = false;
                
                DB::commit_transaction();
                
                $response = array(
                        'error'             => false,  
                        'message'           => $message, 
                        'delete_commande'   => $delete_commande,
                );
            }
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    public function post_addProduct(){
        $code = Input::post('code');
        $name = Input::post('name');
        $prix = Input::post('prix');
        $type = Input::post('type');
        try {
            DB::start_transaction();
            $product = new Model_Product();
            
            $product->set(array(
                'code'    => $code,
                'name'    => $name,
                'price'    => $prix,
                'type'  => $type,
            ));
            
            if (!$product->save()) {
                throw new Exception($product->validation()->show_errors());
            }
            
            DB::commit_transaction();
            
            $response = array(
              'error'       => false,  
              'message'     => 'Produit créé avec succès',  
            );
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    public function post_getTypes(){
        try 
        {
            $types = Model_Product_Type::find();
            $message = 'Récupération des types';
                
                DB::commit_transaction();
                
                $response = array(
                        'error'             => false,  
                        'message'           => $message, 
                        'types'             => $types,
                );
            
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
}