<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\APIS\QzDetailsController;

class QzOkdata extends Model
{
    protected $table = 'qz_okdata';
    public $timestamps = false;
    public function okdata(){
        $QzDetails = new QzDetailsController();
        $doExecute = $QzDetails->getOkdata();
        return $doExecute;
    }
}
