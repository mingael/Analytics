//
//  Analytics
//
// appInfo:
//   date: "2020720"
//   str: Mon Jul 20 2020 19:53:15 GMT+0900 (대한민국 표준시) {}
//   time: 1595242395762
// browser:
//   platform: "Win32"
//   userAgeent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36"
// language:
//   lang: "ko-kr"
//   langType: "ko"
// location:
//   host: "localhost"
//   param: {"": undefined}
//   port: 80
//   protocol: "http"
//   url: "http://localhost/Analytics/"
//
//
let url = '';
let win = window;
let nav = navigator;
let doc = document;

/**
 * 접속시간
 */
function getAppInfo() {
  let app = new Date();
  return {
    str: app,
    date:
      app.getFullYear().toString() +
      (app.getMonth() + 1).toString() +
      app.getDate().toString(),
    time: app.getTime(),
  };
}

/**
 * 사용 언어
 *
 * ko-KR : Chrome, IE, FireFox
 * ko : Edge
 */
function getLanguage() {
  let lang = nav.language || nav.userLanguage;
  lang = lang.toLowerCase();

  let langType;
  if (lang.length > 2) {
    langType = lang.substr(0, 2);
  }

  return {
    lang: lang,
    langType: langType,
  };
}

/**
 * Location 정보
 */
function getLocation() {
  let loc = win.location;
  let protocol = loc.protocol.replace(/[^a-zA-Z]+/i, "");

  let port = loc.port;
  if (port == null || port == "") {
    port = protocol == "http" ? 80 : protocol == "https" ? 443 : "";
  }

  let search = loc.search;
  let param = search.substr(search.indexOf("?") + 1);

  return {
    url: loc.href,
    protocol: protocol,
    port: port,
    host: loc.host,
    param: param,
  };
}

/**
 * 파라미터 정보 가져오기
 */
function getParameter() {
  let loc = win.location;
  let search = loc.search;
  search = search.substr(search.indexOf("?") + 1);
  let params = search.split("&");

  let param = {};
  for (var i = 0; i < params.length; i++) {
    var tmp = params[i].split("=");
    param[tmp[0]] = tmp[1];
  }

  let arr = new Array();
  arr['str'] = search;
  arr['obj'] = param;

  return arr;
}

/**
 * Browser
 */
function getBrowser() {
  let agent = nav.userAgent.toLowerCase();

  return {
    codeName: nav.appCodeName,
    name: nav.appName,
    version: nav.appVersion,
    platform: nav.platform,
    product: nav.product,
    userAgent: nav.userAgent,
    type: isMobile(agent) ? 'M' : 'W',
    device: getDevice(agent),
  };
}

/**
 * 모바일 여부
 */
function isMobile(agent) {
  let filter = /iphone|ipad|ipod|android/i;
  if(filter.test(agent)) {
    return true;
  } else {
    return false;
  }
}

/**
 * 기기
 */
function getDevice(agent) {
  //let devices = ['Firefox', 'Seamonkey', 'Chrome', 'Chromium', 'Safari', 'Opera'];
  if(/trident|msie/.test(agent)) {
    return 'Internet Explorer';
  } else if(/chrome/.test(agent)) {
    return 'Chrome';
  } else if(/edge/.test(agent)) {
    return 'Edge';
  } else if(/firefox/.test(agent)) {
    return 'Firefox';
  } else if(/opera/.test(agent)) {
    return 'Opera';
  } else if(/whale/.test(agent)) {
    return 'Naver Whale';
  } else if(/samsungbrowser/.test(agent)) {
    return 'Samsung Browser';
  } else if(/crios/.test(agent)) {
    return 'Chrome Mobile';
  } else if(/chromium/.test(agent)) {
    return 'Chromium';
  } else if(/seamonkey/.test(agent)) {
    return 'Seamonkey';
  } else if(/safari/.test(agent)) {
    return 'Safari';
  }
  return '';
}

// cookie
let cookie = doc.cookie;

console.log(win);
console.log(nav);
console.log(doc);

/**
 * fetch
 */
function postSend(url, data) {
  return fetch(url, {
    method: "POST",
    cache: 'no-cache',
    credentials: 'same-origin',
    hreaders: new Headers({
      'Content-Type': 'application/json; charset=utf-8'
    }),
    body: JSON.stringify(data)
  }).then(response => response.json());
}

/**
 * load 실행
 */
function collect(url) {
  url = url;

  let msg = {
    appInfo: getAppInfo(),
    language: getLanguage(),
    location: getLocation(),
    browser: getBrowser(),
  };
  console.log(msg);

  postSend(url, msg)
    .then(res => console.log(res)) // Result from the `response.json()` call
    .catch(error => console.log());
}


/**
 * close / unload
 */
window.addEventListener('unload', function() {
  alert('?');
  // postSend(url, msg);
});