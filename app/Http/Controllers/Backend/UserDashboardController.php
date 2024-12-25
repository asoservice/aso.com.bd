<?php

namespace App\Http\Controllers\Backend;

// use App\DataTables\ConsumerTransactionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\DataTables\UserBookingsDataTable;
use App\DataTables\UserServiceMenDataTable;
use App\DataTables\ReviewsDataTable;
use App\DataTables\ProviderDocumentDataTable;
use App\DataTables\WithdrawRequestDataTable;
use App\Enums\RoleEnum;
use App\DataTables\ServicemanWithdrawRequestDataTable;

class UserDashboardController extends Controller
{
    // public function index(ConsumerTransactionsDataTable $dataTable)
    // {
    //     return $this->repository->index($dataTable);
    // }

    public function generalInfo($id)
    { 
        if(self::checkPermission()){
            $user = Helpers::getConsumerById($id);
            $role = Helpers::getRoleByUserId($id); 
    
            if ($role == RoleEnum::PROVIDER) {
                $bookings = Booking::getFilteredBookings($id);
            } elseif ($role == RoleEnum::SERVICEMAN) {
                
                $bookings = Booking::getFilteredBookings(null, $id);
            } else {
                
                $bookings = Booking::getFilteredBookings();
            }
    
            return view('backend.user-dashboard.general-info', ['bookings' => $bookings, 'user' => $user]);
        }
        return back()->with('message', 'unautheticated');
    }

    public function getBookings(UserBookingsDataTable $dataTable)
    {
        $user = Helpers::getConsumerById(request()->id);
        if(self::checkPermission()){
            return $dataTable->render('backend.user-dashboard.bookings' , ['user' => $user]);
        }
        return back()->with('message', 'unautheticated');
    }
    
    public function getServicemen(UserServiceMenDataTable $dataTable)
    {
        $user = Helpers::getConsumerById(request()->id);
        return $dataTable->render('backend.user-dashboard.servicemen' , ['user' => $user]);
    }

    public function getUserReviews(ReviewsDataTable $dataTable)
    {
        $user = Helpers::getConsumerById(request()->id);

        if(self::checkPermission()){
            return $dataTable->render('backend.user-dashboard.reviews' , ['user' => $user]);
        }
        return back()->with('message', 'unautheticated');
    }

    public function getUserDocuments(ProviderDocumentDataTable $dataTable)
    {
        $user = Helpers::getConsumerById(request()->id);

        return $dataTable->render('backend.user-dashboard.documents' , ['user' => $user]);
    }
    
    public function getProviderWithdrawRequests(WithdrawRequestDataTable $dataTable)
    {
        $user = Helpers::getConsumerById(request()->id);

        return $dataTable->render('backend.user-dashboard.withdraw-requests' , ['user' => $user]);
    }

    public function getServicemanWithdrawRequests(ServicemanWithdrawRequestDataTable $dataTable)
    {
        $user = Helpers::getConsumerById(request()->id);

        if(self::checkPermission()){
            return $dataTable->render('backend.user-dashboard.withdraw-requests' , ['user' => $user]);
        }
        return back()->with('danger', 'unautheticated');
    }

    
    public function checkPermission()
    {
        $authUser = auth()->user();
        
        if(Helpers::getCurrentRoleName() == RoleEnum::PROVIDER)
        {
            $servicemanIds = $authUser->servicemans->pluck('id')->toArray();
            return in_array(request()->id,$servicemanIds);
        }
        return true;
    }

}
