import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Swal from 'sweetalert2'
window.Swal = Swal;

import Sortable from 'sortablejs';
window.Sortable = Sortable;
