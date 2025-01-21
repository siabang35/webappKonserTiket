namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Concert;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'concert_name' => 'required|string|max:255',
            'ticket_count' => 'required|integer|min:1|max:10',
        ]);

        try {
            $concert = Concert::where('name', $validated['concert_name'])->firstOrFail();

            $order = Order::create([
                'user_id' => auth()->id(),
                'concert_name' => $validated['concert_name'],
                'ticket_count' => $validated['ticket_count'],
                'total_price' => $concert->ticket_price * $validated['ticket_count'],
            ]);

            Log::info('Order berhasil', ['order_id' => $order->id]);

            return redirect()->route('konfirmasi')->with('success', 'Tiket berhasil dipesan!');
        } catch (\Exception $e) {
            Log::error('Error order: '.$e->getMessage());
            return back()->withErrors('Gagal memesan tiket. Silakan coba lagi.');
        }
    }
}
