import { HttpClient } from './http.js';

const http = new HttpClient('/api');

const increase = id => {
  return http.putJson(`/view/${id}`);
};
const getCount = id => {
  return http.getJson(`/view/${id}`).then(r => r.count);
};
const applyCount = val => {
  document.getElementById('value').innerText = val;
};
let interval = null;

window.getRandomImg = async () => {
  if (null !== interval)
    clearInterval(interval);

  const r = await http.postJson('/id');
  let src = `/img/${r.id}.jpg`;
  const i = await fetch(src);

  if (404 === i.status)
    src = 'img/empty.jpg';
  else {
    await increase(r.id);
    applyCount(await getCount(r.id));
    interval = setInterval(async () => applyCount(await getCount(r.id)), 5*1000);
  }

  document.getElementsByTagName('img')[0].src = src;
}

window.getRandomImg();
