<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class AdController extends Controller
{
    public function handle(Request $request, $id)
    {
        // 1) لو الطلب من التطبيق
        if ($request->header('X-APP-REQUEST') === 'mobile-app') {

            // 2) لو المستخدم مسجّل دخول
            if ($request->user()) {
                return response()->json([
                    'status' => 'ok',
                    'data' => Article::findOrFail($id),
                ]);
            }

            // 3) لو من التطبيق لكن بدون تسجيل دخول
            return response()->json([
                'status' => 'auth_required',
                'article_id' => $id,
            ]);
        }

        // 4) لو من المتصفح → تحويل لمتجر التطبيقات
        return redirect('');
    }
}
