import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { api } from '../helpers/config'

declare global {
  interface Window {
    Pusher: any;
  }
}

window.Pusher = Pusher;

let echo: Echo<any> | null = null;

export const initEcho = async (): Promise<Echo<any>> => {
  if (echo) return echo;

  const { data } = await api.get('/broadcast-config');

  echo = new Echo({
    broadcaster: 'pusher',
    key: data.key,
    cluster: data.cluster,
    forceTLS: data.useTLS,
  });

  return echo;
};
