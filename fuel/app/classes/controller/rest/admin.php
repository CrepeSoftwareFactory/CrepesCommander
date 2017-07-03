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
}