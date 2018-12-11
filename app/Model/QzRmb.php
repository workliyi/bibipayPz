<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\APIS\QzDetailsController;
class QzRmb extends Model
{
    protected $table = 'qz_rmb';
    public $timestamps = false;
<<<<<<< HEAD
=======
    public function Rmb(){
        $QzDetails = new QzDetailsController();
        $doExecute = $QzDetails->getRmb();
        return $doExecute;
    }
>>>>>>> 2282055b534a55a8d8f048cd3fb42dde458ea4ad
}
