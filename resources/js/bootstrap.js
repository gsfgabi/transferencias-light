import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import Livewire
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
