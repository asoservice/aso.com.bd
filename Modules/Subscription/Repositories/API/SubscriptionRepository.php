<?php

namespace Modules\Subscription\Repositories\API;

use App\Enums\TransactionType;
use App\Enums\WalletPointsDetail;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Http\Traits\WalletPointsTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Subscription\Entities\Plan;
use Modules\Subscription\Entities\UserSubscription;
use Modules\Subscription\Enums\PaymentAllowed;
use Nwidart\Modules\Facades\Module;
use Prettus\Repository\Eloquent\BaseRepository;

class SubscriptionRepository extends BaseRepository
{
    use WalletPointsTrait;

    public $plan;

    public function model()
    {
        $this->plan = new Plan();

        return UserSubscription::class;
    }

    public function isAlreadyFreeTrialPurchased()
    {
        return UserSubscription::where('is_included_free_trial', true)?->where('user_id', Helpers::getCurrentUserId())?->exists();
    }

    public function verfiyFreeTrialPlan()
    {
        $settings = Helpers::getSettings();
        if ($settings['subscription_plan']['free_trial_enabled']) {
            if (! $this->isAlreadyFreeTrialPurchased()) {
                return $settings['subscription_plan']['free_trial_days'];
            }
            throw new Exception('Free trial plan all ready purchased.', 400);
        }

        throw new Exception('Free trial plan feature is not enable.', 400);
    }

    public function purchase($request)
    {
        DB::beginTransaction();
        try {

            $addDays = null;
            if ($request->included_free_trial) {
                $addDays = $this->verfiyFreeTrialPlan();
            }

            $existingSubscription = $this->model
                ->where('user_id', Helpers::getCurrentUserId())
                ->where('is_active', true)
                ->first();

            if ($existingSubscription) {
                $existingSubscription->update(['is_active' => false]);
            }

            $plan = Plan::find($request->input('plan_id'));
            $subscription = $this->model->create([
                'user_id' => Helpers::getCurrentUserId(),
                'user_plan_id' => $plan->id,
                'start_date' => Carbon::now(),
                'end_date' => $this->model->calculateEndDate($plan->duration, $addDays),
                'total' => $plan->price,
                'allowed_max_services' => $plan->max_services,
                'allowed_max_addresses' => $plan->max_addresses,
                'allowed_max_servicemen' => $plan->max_servicemen,
                'allowed_max_service_packages' => $plan->max_service_packages,
                'is_active' => true,
            ]);
            DB::commit();
            if ($request->wallet_balance) {
                if ($this->verifyWallet(Helpers::getCurrentUserId(), $request->wallet_balance)) {
                    $this->debitProviderWallet(Helpers::getCurrentUserId(), $request->wallet_balance, WalletPointsDetail::SUBSCRIPTION, null);
                }
            } elseif ($request->payment_method != 'cash') {
                if (! in_array($request->payment_method, array_column(PaymentAllowed::cases(), 'value'))) {
                    throw new Exception($request->payment_method.' payment method not allow for purchase subscription.', 400);
                }

                $module = Module::find($request->payment_method);
                if (! is_null($module) && $module?->isEnabled()) {
                    $request->merge(['type' => 'subscription']);
                    $moduleName = $module->getName();
                    $payment = 'Modules\\'.$moduleName.'\\Payment\\'.$moduleName;
                    if (class_exists($payment) && method_exists($payment, 'getIntent')) {
                        return $payment::getIntent($subscription, $request);
                    }

                    throw new Exception('Payment module class or method not found.', 400);
                }

                throw new Exception('Selected payment method is invalid', 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Purchase Successfully!',
            ]);

        } catch (Exception $e) {

            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function debitProviderWallet($consumer_id, $balance, $detail)
    {
        $wallet = $this->getWallet($consumer_id);
        if ($wallet) {
            if ($wallet->balance >= $balance) {
                $wallet->decrement('balance', $balance);
                $this->debitTransaction($wallet, $balance, $detail, $consumer_id);

                return $wallet;
            }

            throw new ExceptionHandler(__('errors.insufficient_wallet_balance'), 400);
        }
    }

    public function debitTransaction($model, $amount, $detail, $consumer_id)
    {
        return $this->storeTransaction($model, TransactionType::DEBIT, $detail, $amount, $consumer_id);
    }

    public function storeTransaction($model, $type, $detail, $amount, $consumer_id)
    {
        return $model->transactions()->create([
            'amount' => $amount,
            'provider_id' => $consumer_id,
            'detail' => $detail,
            'type' => $type,
            'from' => $this->getRoleId(),
        ]);
    }

    public function verifyWallet($consumer_id, $balance)
    {
        if ($balance > 0.00) {
            if (Helpers::walletIsEnable()) {
                $walletBalance = $this->getWalletBalance($consumer_id);
                if ($walletBalance >= $balance) {
                    return true;
                }

                throw new Exception(__('errors.insufficient_wallet_balance'), 400);
            }

            throw new Exception(__('errors.wallet_feature_disabled'), 400);
        }
    }

    public function getPlans($request)
    {
        $plans = $this->plan->where('status', true);

        return $plans->latest('created_at')->paginate($request->paginate ?? $plans->count());
    }
}
