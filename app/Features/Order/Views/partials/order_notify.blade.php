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

let ws = null;
const wsUrl = @json(config('websocket.url'));

const RECONNECT_INTERVAL = 5000;
const HEARTBEAT_INTERVAL = 30000;
const HEARTBEAT_TIMEOUT = 10000;

let heartbeatTimer = null;
let serverTimeoutTimer = null;
let isForcedClose = false;

function connectWebSocket() {
    if (ws) {
        ws.close();
    }

    ws = new WebSocket(wsUrl);

    ws.onopen = () => {
        isForcedClose = false;
        ws.send(JSON.stringify({ type: 'subscribe', channel: 'orders' }));
        
        if (wsStatusDot) wsStatusDot.className = 'rounded-circle bg-success';
        if (wsStatusText) {
            wsStatusText.textContent = wsStatusConnected;
            wsStatusText.className = 'small text-success';
        }

        startHeartbeat();

        console.log('WebSocket connection established.');
    };

    ws.onclose = () => {
        resetHeartbeat();
        
        if (wsStatusDot) wsStatusDot.className = 'rounded-circle bg-danger';
        if (wsStatusText) {
            wsStatusText.textContent = wsStatusDisconnected;
            wsStatusText.className = 'small text-danger';
        }

        if (!isForcedClose) {
            setTimeout(() => {
                console.log('Attempting to reconnect WebSocket...');
                connectWebSocket();
            }, RECONNECT_INTERVAL);
        }
    };

    ws.onerror = (error) => {
        console.error('WebSocket Error:', error);
        if (wsStatusDot) wsStatusDot.className = 'rounded-circle bg-warning';
        if (wsStatusText) {
            wsStatusText.textContent = wsStatusError;
            wsStatusText.className = 'small text-warning';
        }
    };

    ws.onmessage = (event) => {
        startHeartbeat();

        try {
            const data = JSON.parse(event.data);

            if (data === 'pong' || data.type === 'pong') {
                console.log('Received heartbeat pong from server');
                return; 
            }

            if (data.event && data.event.includes('OrderCreated')) {
                handleNewOrder(data.data && data.data.order ? data.data.order : null);
            }
        } catch (e) {
            console.error('Invalid message:', event.data);
        }
    };
}

function startHeartbeat() {
    resetHeartbeat();

    heartbeatTimer = setTimeout(() => {
        if (ws && ws.readyState === WebSocket.OPEN) {
            console.log('Sending heartbeat ping...');
            ws.send(JSON.stringify({ type: 'ping' }));
            serverTimeoutTimer = setTimeout(() => {
                console.warn('Heartbeat timeout. Closing connection...');
                ws.close(); 
            }, HEARTBEAT_TIMEOUT);
        }
    }, HEARTBEAT_INTERVAL);
}

function resetHeartbeat() {
    clearTimeout(heartbeatTimer);
    clearTimeout(serverTimeoutTimer);
}


function handleNewOrder(order) {
    if (!order) return;

    const audio = document.getElementById('order-audio');
    if (audio) {
        audio.currentTime = 0;
        audio.play();
    }

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
        iconColor: '#198754',
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


document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        if (!ws || ws.readyState === WebSocket.CLOSED) {
            console.log('Page visible, reconnecting...');
            connectWebSocket();
        }
    }
});

connectWebSocket();
</script>