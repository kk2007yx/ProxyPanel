<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\PaymentController;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
    public function info(Request $request, $id)
    {
        $order = Order::query()->with(['goods.category'])->find($id);

        return $this->sendJson($order);
    }

    public function pay(Request $request, $id)
    {
        $order = Order::query()->find($id);
        $user = $request->user('api');
        if ($order->pay_type === 0) {
            if ($user->credit < $order->amount) {
                return $this->sendError(trans('order.pay.no_money'));
            }
            $order->complete();
            $user->update(['money' => $user->credit - $order->amount]);

            return response()->json(['code' => 1, 'url' => '']);
        }
        $request->merge(['id' => $order->id, 'type' => $order->pay_type, 'amount' => $order->amount]);
        if ($payment = Payment::where(['user_id' => $user->id, 'order_id' => $order->id, 'status' => 0])->whereNotNull('url')->first()) {
            $minute = floor((time() - strtotime($payment->created_at)) % 86400 / 60);
            if ($minute > 15) {
                $payment->delete();
                // 生成支付单
                $data = PaymentController::getClient()->purchase($request);
                $data = $data->getData();
                if ($data->status == 'fail'){
                    return $this->sendError($data->message);
                }
                $url = $data->url;
            } else {
                $url = $payment->url;
            }
        } else {
            // 生成支付单
            $data = PaymentController::getClient($order->pay_way)->purchase($request);
            $data = $data->getData();
            if ($data->status == 'fail'){
                return $this->sendError($data->message);
            }
            $url = $data->url;
        }

        return response()->json(['code' => 1, 'url' => $url]);
    }
}
