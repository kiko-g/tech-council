function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function (k) {
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function formatParams( params ){
  return "?" + Object
    .keys(params)
    .map(function(key){
      return key+"="+encodeURIComponent(params[key])
    })
    .join("&");
}

function sendAjaxRequest(method, url, data, handler) {
  if(method === 'get' && data) {
    url += formatParams(data);
  }

  let request = new XMLHttpRequest();
  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);

  if(method === 'get') {
    request.send();
  } else {
    request.send(encodeForAjax(data));
  }
}

let isAuthenticated = false;
sendAjaxRequest('get', '/auth/check', null, function () {
  if (this.response) isAuthenticated = true;
  else isAuthenticated = false;
});