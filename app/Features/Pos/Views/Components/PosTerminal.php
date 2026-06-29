<?php
namespace App\Features\Pos\Views\Components;

use Livewire\Component;
use App\Features\Cart\Services\CartService;
use App\Features\Product\Models\Product;
use App\Features\Order\Enums\OrderType;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;
use App\Features\ProductCategory\Models\ProductCategory;
use Livewire\Attributes\Layout; 
use App\Features\Order\Support\OrderCreationStrategies\OrderStrategyFactory;
use App\Features\Order\DTOs\CreateOrderDto;
use Illuminate\Support\Facades\Lang;

class PosTerminal extends Component
{
    protected CartService $cartService;

    public $posCartId;
    public $cashReceived = 0;
    public $note = '';
    public $paymentMethod = 'CASH';

    public $selectedCategoryId = null;

    public $errorMessage = '';
    public $successMessage = '';

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
        $staffId = Auth::id() ?? 'default';
        $this->posCartId = 'pos:staff:' . $staffId;
    }

    public function mount()
    {
        $firstCategory = ProductCategory::orderBy('sort_order', 'asc')->first();
        if ($firstCategory) {
            $this->selectedCategoryId = $firstCategory->id;
        }
    }

    /**
     * 业务逻辑：切换分类
     */
    public function selectCategory($categoryId)
    {
        $this->selectedCategoryId = $categoryId;
    }

    /**
     * 计算购物车明细（计算折扣价、小计、总价）
     */
    public function getCartDetailsProperty()
    {
        return $this->cartService->getCartDetails($this->posCartId);
    }

    /**
     * 业务逻辑 1：添加商品/增加数量
     */
    public function addToCart($productId)
    {
        $this->clearMessages();
        try {
            $this->cartService->addToCart($this->posCartId, $productId, 1);
        } catch (BusinessException $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    /**
     * 业务逻辑 2：减少商品数量
     */
    public function decreaseQuantity($productId)
    {
        $this->clearMessages();
        $this->cartService->removeQuantityFromCart($this->posCartId, $productId, 1);
    }

    /**
     * 业务逻辑 3：一键清空当前单据
     */
    public function clearCart()
    {
        $this->clearMessages();
        $this->cartService->clearCart($this->posCartId);
    }

    /**
     * 业务逻辑 4：实时计算现金找零
     */
    public function getChangeProperty()
    {
        $total = (float) ($this->cartDetails['total_price'] ?? 0);
        $received = (float) $this->cashReceived;
        return $received > $total ? number_format($received - $total, 2, '.', '') : '0.00';
    }

    /**
     * 业务逻辑 5：线下结账并生成正式订单
     */
    public function checkout(OrderStrategyFactory $factory)
    {
        if (empty($this->cartDetails['cart'])) {
            $this->errorMessage = __('pos.cart_empty');
            return;
        }

        try {
            $this->errorMessage = '';
            $this->successMessage = '';

            $strategy = $factory->create(OrderType::WALK_IN);

            $dto = new CreateOrderDto(
                customerId: null,
                addressId: null,
                orderType: OrderType::WALK_IN,
                reserveTime: now()->toDateTimeString(),
                note: $this->note ?: null,
                couponCustomerId: null
            );
            
            $order = $strategy->createOrder($dto);

            $this->reset(['cashReceived', 'note', 'paymentMethod']);
            unset($this->cartDetails);

            $this->successMessage = __('pos.checkout_success', ['sequence' => $order->daily_sequence]);

        } catch (BusinessException $e) {
            $this->errorMessage = Lang::has('pos.' . $e->getMessage()) ? __('pos.' . $e->getMessage()) : $e->getMessage();
        } catch (\Exception $e) {
            $this->errorMessage = __('pos.checkout_failed');
            \Log::error('POS checkout error: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    private function clearMessages()
    {
        $this->errorMessage = '';
        $this->successMessage = '';
    }

    #[Layout('layouts.pos')]
    public function render()
    {
        $categories = ProductCategory::orderBy('sort_order', 'asc')->get();

        $query = Product::query();

        if ($this->selectedCategoryId === 'uncategorized') {
            $query->whereNull('product_category_id');
        } elseif ($this->selectedCategoryId) {
            $query->where('product_category_id', $this->selectedCategoryId);
        } else {
            
        }

        $products = $query->get();

        return view('pos::pos-terminal', [
            'categories'    => $categories,
            'products'      => $products,
            'cartItems'     => $this->cartDetails['cart'],
            'totalPrice'    => $this->cartDetails['total_price'],
            'totalQuantity' => $this->cartDetails['total_quantity'],
        ]);
    }
}