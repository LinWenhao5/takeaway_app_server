<script>
const wsStatusConnected = @json(__('orders.ws_connected'));
const wsStatusDisconnected = @json(__('orders.ws_disconnected'));
const wsStatusError = @json(__('orders.ws_error'));
const newOrderTitle = @json(__('orders.new_order_title'));
const newOrderConfirm = @json(__('orders.refresh_order_list'));
const orderNumberLabel = @json(__('orders.order_number'));
const totalPriceLabel = @json(__('orders.total'));

const wsStatusDot = document.getElementById('ws-status-dot');
const wsStatusText = document.getElementById('ws-status-text');
const wsStatusBtn = document.getElementById('ws-status');
const ws = new WebSocket(@json(config('websocket.url')));

ws.onopen = () => {
    if (wsStatusDot) wsStatusDot.className = 'rounded-circle bg-success';
    if (wsStatusText) wsStatusText.textContent = wsStatusConnected;
    if (wsStatusText) wsStatusText.className = 'small text-success';
};
ws.onclose = () => {
    if (wsStatusDot) wsStatusDot.className = 'rounded-circle bg-danger';
    if (wsStatusText) wsStatusText.textContent = wsStatusDisconnected;
    if (wsStatusText) wsStatusText.className = 'small text-danger';
};
ws.onerror = () => {
    if (wsStatusDot) wsStatusDot.className = 'rounded-circle bg-warning';
    if (wsStatusText) wsStatusText.textContent = wsStatusError;
    if (wsStatusText) wsStatusText.className = 'small text-warning';
};
ws.onmessage = (event) => {
    try {
        const data = JSON.parse(event.data);
        if (data.event && data.event.includes('OrderCreated')) {
            const audio = document.getElementById('order-audio');
            if (audio) {
                audio.currentTime = 0;
                audio.play();
            }

            const order = data.data && data.data.order ? data.data.order : null;
            if (order) {
                const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

                Swal.fire({
                    title: newOrderTitle,
                    html: `<b>${orderNumberLabel} ${order.id}</b><br>${totalPriceLabel}: <b>€${order.total_price}</b>`,
                    icon: 'success',
                    confirmButtonText: newOrderConfirm,
                    timer: 5000,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    background: isDark ? '#23272b' : '#fff',
                    color: isDark ? '#fff' : '#23272b',
                    iconColor: isDark ? '#198754' : '#198754',
                    didOpen: () => {
                        Swal.getConfirmButton().focus();
                    }
                }).then((result) => {
                    if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newList = doc.querySelector('#order-list');
                                if (newList) {
                                    document.querySelector('#order-list').innerHTML = newList.innerHTML;
                                }
                            });
                    }
                });
            }
        }
    } catch (e) {
        console.error('Invalid message:', event.data);
    }
};
</script>